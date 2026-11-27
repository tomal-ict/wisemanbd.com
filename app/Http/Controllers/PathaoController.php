<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Services\PathaoService;

class PathaoController extends Controller
{
    protected $pathaoService;

    public function __construct(PathaoService $pathaoService)
    {
        $this->pathaoService = $pathaoService;
    }

    /**
     * Dispatch order to Pathao Courier
     */
    public function sendToPathao(Request $request)
    {
        $orderId = $request->order_id;
        $order = Order::findOrFail($orderId);

        if (!$this->pathaoService->isActivated()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Pathao Courier is not activated. Enable it from Activation page.']);
            }
            flash(translate('Pathao Courier is not activated. Enable it from Activation page.'))->error();
            return back();
        }

        if (!$this->pathaoService->isConfigured()) {
            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Pathao credentials missing. Configure them in Pathao Configuration.']);
            }
            flash(translate('Pathao credentials missing. Configure them in Pathao Configuration.'))->error();
            return back();
        }

        $options = [
            'store_id' => $request->store_id,
            'recipient_city' => $request->recipient_city,
            'recipient_zone' => $request->recipient_zone,
            'recipient_area' => $request->recipient_area,
            'item_weight' => $request->item_weight,
            'delivery_type' => $request->delivery_type ?? 48,
            'item_type' => $request->item_type ?? 2,
            'special_instruction' => $request->special_instruction,
            'item_description' => $request->item_description,
        ];

        // Filter out nulls
        $options = array_filter($options, function ($val) {
            return !is_null($val) && $val !== '';
        });

        $result = $this->pathaoService->createOrder($order, $options);

        if ($result['success']) {
            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => $result['message'],
                    'consignment_id' => $result['consignment_id'],
                    'delivery_fee' => $result['delivery_fee'] ?? null,
                ]);
            }
            flash($result['message'])->success();
            return back();
        }

        if ($request->ajax()) {
            return response()->json([
                'success' => false,
                'message' => $result['message'],
            ], 422);
        }

        flash($result['message'])->error();
        return back();
    }

    /**
     * Track Order from Pathao
     */
    public function track(Request $request)
    {
        $consignmentId = $request->consignment_id;
        if (empty($consignmentId)) {
            return response()->json(['success' => false, 'message' => 'Consignment ID is required'], 400);
        }

        $result = $this->pathaoService->trackOrder($consignmentId);
        return response()->json($result);
    }

    /**
     * Get City list
     */
    public function getCities(Request $request)
    {
        $cities = $this->pathaoService->getCities();
        return response()->json($cities);
    }

    /**
     * Get Zones for City
     */
    public function getZones(Request $request)
    {
        $cityId = (int) $request->city_id;
        if (!$cityId) {
            return response()->json([]);
        }

        $zones = $this->pathaoService->getZones($cityId);
        return response()->json($zones);
    }

    /**
     * Get Areas for Zone
     */
    public function getAreas(Request $request)
    {
        $zoneId = (int) $request->zone_id;
        if (!$zoneId) {
            return response()->json([]);
        }

        $areas = $this->pathaoService->getAreas($zoneId);
        return response()->json($areas);
    }

    /**
     * Webhook endpoint for Pathao Order Status Updates
     */
    public function webhook(Request $request)
    {
        $payload = $request->all();
        \Log::info('Pathao Webhook Received', $payload);

        $consignmentId = $payload['consignment_id'] ?? null;
        $orderStatus = $payload['order_status'] ?? null;

        if ($consignmentId && $orderStatus) {
            $order = Order::where('pathao_consignment_id', $consignmentId)
                ->orWhere('tracking_code', $consignmentId)
                ->first();

            if ($order) {
                $order->pathao_order_status = $orderStatus;

                // Sync internal delivery status
                $mappedStatus = match (strtolower($orderStatus)) {
                    'delivered' => 'delivered',
                    'cancelled' => 'cancelled',
                    'returned' => 'cancelled',
                    'in_transit', 'picked_up' => 'on_the_way',
                    default => null
                };

                if ($mappedStatus) {
                    $order->delivery_status = $mappedStatus;
                }

                $order->save();
            }
        }

        return response()->json(['status' => 'success']);
    }
}
