<?php

namespace App\Services;

use App\Models\BusinessSetting;
use App\Models\Order;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class PathaoService
{
    protected $clientId;
    protected $clientSecret;
    protected $username;
    protected $password;
    protected $sandbox;
    protected $baseUrl;
    protected $storeId;

    public function __construct()
    {
        $this->clientId = BusinessSetting::where('type', 'pathao_client_id')->value('value');
        $this->clientSecret = BusinessSetting::where('type', 'pathao_client_secret')->value('value');
        $this->username = BusinessSetting::where('type', 'pathao_username')->value('value');
        $this->password = BusinessSetting::where('type', 'pathao_password')->value('value');
        $this->sandbox = (bool) BusinessSetting::where('type', 'pathao_sandbox')->value('value');
        $this->storeId = BusinessSetting::where('type', 'pathao_store_id')->value('value');

        $this->baseUrl = $this->sandbox
            ? 'https://courier-api-sandbox.pathao.com'
            : 'https://api-hermes.pathao.com';
    }

    public function isConfigured(): bool
    {
        if ($this->sandbox) {
            return true;
        }
        return !empty($this->clientId) && !empty($this->clientSecret) && !empty($this->username) && !empty($this->password);
    }

    public function isSandbox(): bool
    {
        return $this->sandbox;
    }

    public function isActivated(): bool
    {
        return (bool) BusinessSetting::where('type', 'pathao_courier_activation')->value('value');
    }

    public function getHttpTimeout(): int
    {
        return $this->sandbox ? 25 : 10;
    }

    /**
     * Issue or retrieve cached OAuth access token
     */
    public function getAccessToken(): ?string
    {
        $cacheKey = 'pathao_access_token_' . ($this->sandbox ? 'sandbox' : 'live');
        if (Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }

        $timeout = $this->sandbox ? 30 : 10;
        $url = $this->sandbox ? 'https://courier-api-sandbox.pathao.com' : 'https://api-hermes.pathao.com';

        // In sandbox mode, use Pathao official test credentials
        // In live mode, use merchant configured credentials
        $clientId = $this->sandbox ? '7N1aMJQbWm' : $this->clientId;
        $clientSecret = $this->sandbox ? 'wRcaibZkUdSNz2EI9ZyuXLlNrnAv0TdPUPXMnD39' : $this->clientSecret;
        $username = $this->sandbox ? 'test@pathao.com' : $this->username;
        $password = $this->sandbox ? 'lovePathao' : $this->password;

        try {
            $response = Http::timeout($timeout)->asJson()->acceptJson()->post($url . '/aladdin/api/v1/issue-token', [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => 'password',
                'username' => $username,
                'password' => $password,
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $token = $data['access_token'] ?? null;
                $expiresIn = (int) ($data['expires_in'] ?? 3600);

                if ($token) {
                    Cache::put($cacheKey, $token, max(60, $expiresIn - 300));
                    return $token;
                }
            }

            Log::error('Pathao issue-token failed', [
                'url' => $url,
                'username' => $username,
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Pathao issue-token exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Get list of stores from Pathao merchant account
     */
    public function getStores(): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [];
        }

        try {
            $response = Http::timeout($this->getHttpTimeout())->withToken($token)->acceptJson()->get($this->baseUrl . '/aladdin/api/v1/stores');
            if ($response->successful()) {
                return $response->json()['data']['data'] ?? [];
            }
        } catch (\Exception $e) {
            Log::error('Pathao getStores exception: ' . $e->getMessage());
        }

        return [];
    }

    /**
     * Resolve default numeric Store ID
     */
    public function getDefaultStoreId(): int
    {
        if ($this->sandbox) {
            return 11; // Demo Store on Pathao sandbox
        }

        if (is_numeric($this->storeId) && (int) $this->storeId > 0) {
            return (int) $this->storeId;
        }

        $stores = $this->getStores();
        if (!empty($stores)) {
            // Check matching store name
            if (!empty($this->storeId)) {
                foreach ($stores as $s) {
                    if (strcasecmp(trim($s['store_name'] ?? ''), trim($this->storeId)) === 0) {
                        return (int) $s['store_id'];
                    }
                }
            }

            // Check default store
            foreach ($stores as $s) {
                if (!empty($s['is_default_store'])) {
                    return (int) $s['store_id'];
                }
            }

            // Fallback to first store
            return (int) $stores[0]['store_id'];
        }

        return 0;
    }

    /**
     * Get City List
     */
    public function getCities(): array
    {
        return Cache::remember('pathao_cities', 86400, function () {
            $token = $this->getAccessToken();
            if (!$token) return [];

            try {
                $response = Http::timeout($this->getHttpTimeout())->withToken($token)->acceptJson()->get($this->baseUrl . '/aladdin/api/v1/city-list');
                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error('Pathao getCities exception: ' . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Get Zones by City ID
     */
    public function getZones(int $cityId): array
    {
        return Cache::remember('pathao_zones_' . $cityId, 86400, function () use ($cityId) {
            $token = $this->getAccessToken();
            if (!$token) return [];

            try {
                $response = Http::timeout($this->getHttpTimeout())->withToken($token)->acceptJson()->get($this->baseUrl . "/aladdin/api/v1/cities/{$cityId}/zone-list");
                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error("Pathao getZones({$cityId}) exception: " . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Get Areas by Zone ID
     */
    public function getAreas(int $zoneId): array
    {
        return Cache::remember('pathao_areas_' . $zoneId, 86400, function () use ($zoneId) {
            $token = $this->getAccessToken();
            if (!$token) return [];

            try {
                $response = Http::timeout($this->getHttpTimeout())->withToken($token)->acceptJson()->get($this->baseUrl . "/aladdin/api/v1/zones/{$zoneId}/area-list");
                if ($response->successful()) {
                    return $response->json()['data']['data'] ?? [];
                }
            } catch (\Exception $e) {
                Log::error("Pathao getAreas({$zoneId}) exception: " . $e->getMessage());
            }

            return [];
        });
    }

    /**
     * Format phone number to Pathao standard 11 digits (e.g. 017xxxxxxxx)
     */
    public function formatPhone(?string $phone): string
    {
        if (empty($phone)) {
            return '';
        }

        $digits = preg_replace('/[^0-9]/', '', $phone);

        if (str_starts_with($digits, '8801')) {
            $digits = substr($digits, 2);
        } elseif (str_starts_with($digits, '88001')) {
            $digits = substr($digits, 3);
        }

        if (strlen($digits) == 10 && str_starts_with($digits, '1')) {
            $digits = '0' . $digits;
        }

        return $digits;
    }

    /**
     * Resolve Pathao City and Zone IDs from shipping address
     */
    public function resolveCityAndZone(?string $cityName, ?string $addressText = ''): array
    {
        $cities = $this->getCities();
        $targetCityId = 1; // Default: Dhaka
        $targetZoneId = 303; // Default: Banasree (or default store zone)

        $combinedText = strtolower(trim(($cityName ?? '') . ' ' . ($addressText ?? '')));

        if (!empty($cities)) {
            foreach ($cities as $city) {
                $name = strtolower(trim($city['city_name'] ?? ''));
                if (!empty($name) && str_contains($combinedText, $name)) {
                    $targetCityId = (int) $city['city_id'];
                    break;
                }
            }
        }

        $zones = $this->getZones($targetCityId);
        if (!empty($zones)) {
            foreach ($zones as $zone) {
                $zName = strtolower(trim($zone['zone_name'] ?? ''));
                if (!empty($zName) && strlen($zName) > 2 && str_contains($combinedText, $zName)) {
                    $targetZoneId = (int) $zone['zone_id'];
                    break;
                }
            }
            if ($targetZoneId === 0 && !empty($zones[0]['zone_id'])) {
                $targetZoneId = (int) $zones[0]['zone_id'];
            }
        }

        return [
            'city_id' => $targetCityId,
            'zone_id' => $targetZoneId,
        ];
    }

    /**
     * Create/Send an order to Pathao Courier
     */
    public function createOrder(Order $order, array $options = []): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Pathao API authentication failed. Please check your credentials in Pathao Configuration.',
            ];
        }

        $storeId = !empty($options['store_id']) ? (int) $options['store_id'] : $this->getDefaultStoreId();
        if (!$storeId) {
            return [
                'success' => false,
                'message' => 'Valid Pathao Store ID not found. Please check stores in Pathao panel.',
            ];
        }

        $shipping = json_decode($order->shipping_address);
        $phone = $this->formatPhone($shipping->phone ?? '');
        if (strlen($phone) !== 11) {
            return [
                'success' => false,
                'message' => "Invalid recipient phone number '{$phone}'. Pathao requires an 11-digit Bangladeshi mobile number.",
            ];
        }

        $address = trim($shipping->address ?? '');
        if (strlen($address) < 10) {
            $address = $address . ', ' . ($shipping->city ?? '') . ', ' . ($shipping->country ?? 'Bangladesh');
        }
        if (strlen($address) < 10) {
            $address = str_pad($address, 10, ' ');
        }

        // Determine city and zone
        $cityId = $options['recipient_city'] ?? null;
        $zoneId = $options['recipient_zone'] ?? null;
        $areaId = $options['recipient_area'] ?? null;

        if ($this->sandbox) {
            $cityId = $cityId ?: 1;
            $zoneId = $zoneId ?: 1;
        } elseif (!$cityId || !$zoneId) {
            $resolved = $this->resolveCityAndZone($shipping->city ?? '', $address);
            $cityId = $cityId ?: $resolved['city_id'];
            $zoneId = $zoneId ?: $resolved['zone_id'];
        }

        $merchantOrderId = (string) $order->code;
        if ($this->sandbox) {
            $merchantOrderId .= '-' . time();
        }

        $amountToCollect = ($order->payment_status === 'paid') ? 0 : round($order->grand_total);
        $itemQuantity = max(1, (int) $order->orderDetails->sum('quantity'));
        $weight = isset($options['item_weight']) ? (float) $options['item_weight'] : 0.5;
        if ($weight < 0.5) $weight = 0.5;

        $payload = [
            'store_id' => (int) $storeId,
            'merchant_order_id' => $merchantOrderId,
            'recipient_name' => (string) ($shipping->name ?? 'Customer'),
            'recipient_phone' => (string) $phone,
            'recipient_address' => (string) $address,
            'recipient_city' => (int) $cityId,
            'recipient_zone' => (int) $zoneId,
            'delivery_type' => (int) ($options['delivery_type'] ?? 48),
            'item_type' => (int) ($options['item_type'] ?? 2),
            'special_instruction' => (string) ($options['special_instruction'] ?? 'Wiseman order - please handle with care'),
            'item_quantity' => (int) $itemQuantity,
            'item_weight' => (float) $weight,
            'amount_to_collect' => (int) $amountToCollect,
            'item_description' => (string) ($options['item_description'] ?? ('Order #' . $order->code)),
        ];

        if (!empty($areaId)) {
            $payload['recipient_area'] = (int) $areaId;
        }

        try {
            $response = Http::timeout($this->sandbox ? 25 : 10)
                ->withToken($token)
                ->acceptJson()
                ->asJson()
                ->post($this->baseUrl . '/aladdin/api/v1/orders', $payload);

            $resData = $response->json();

            if ($response->successful() && isset($resData['data']['consignment_id'])) {
                $consignmentId = $resData['data']['consignment_id'];
                $deliveryFee = $resData['data']['delivery_fee'] ?? null;
                $orderStatus = $resData['data']['order_status'] ?? 'Pending';

                $order->pathao_consignment_id = $consignmentId;
                $order->pathao_delivery_fee = $deliveryFee;
                $order->pathao_order_status = $orderStatus;
                $order->tracking_code = $consignmentId;
                $order->save();

                $modeLabel = $this->sandbox ? '[Sandbox Mode]' : '[Live Mode]';

                return [
                    'success' => true,
                    'consignment_id' => $consignmentId,
                    'delivery_fee' => $deliveryFee,
                    'order_status' => $orderStatus,
                    'sandbox' => $this->sandbox,
                    'data' => $resData['data'],
                    'message' => "Order sent to Pathao {$modeLabel}! Consignment ID: {$consignmentId}",
                ];
            }

            $errorMessage = $resData['message'] ?? 'Failed to create order in Pathao.';
            if (isset($resData['errors']) && is_array($resData['errors'])) {
                $flatErrors = [];
                foreach ($resData['errors'] as $field => $errs) {
                    $flatErrors[] = is_array($errs) ? implode(', ', $errs) : $errs;
                }
                $errorMessage .= ' (' . implode('; ', $flatErrors) . ')';
            }

            Log::error('Pathao create order error', ['payload' => $payload, 'response' => $resData]);

            return [
                'success' => false,
                'message' => $errorMessage,
                'data' => $resData,
            ];
        } catch (\Exception $e) {
            Log::error('Pathao create order exception: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Pathao API Error: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Track Order / Get Consignment Status
     */
    public function trackOrder(string $consignmentId): array
    {
        $token = $this->getAccessToken();
        if (!$token) {
            return [
                'success' => false,
                'message' => 'Pathao API authentication failed',
            ];
        }

        try {
            $response = Http::timeout($this->getHttpTimeout())
                ->withToken($token)
                ->acceptJson()
                ->get($this->baseUrl . "/aladdin/api/v1/orders/{$consignmentId}/info");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'data' => $response->json()['data'] ?? [],
                ];
            }

            return [
                'success' => false,
                'message' => $response->json()['message'] ?? 'Unable to fetch tracking info',
            ];
        } catch (\Exception $e) {
            $msg = $e->getMessage();
            if (str_contains(strtolower($msg), 'timed out') || str_contains(strtolower($msg), 'timeout')) {
                $msg = 'Pathao server took too long to respond (timeout). Please try again in a few moments.';
            }
            return [
                'success' => false,
                'message' => $msg,
            ];
        }
    }
}
