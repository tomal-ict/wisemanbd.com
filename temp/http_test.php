<?php
$sid = 'xzhsBbIp11UMeazu3kweNijwLtUHLOSYlEprsIYx';
function get($url, $sid) {
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_COOKIE => 'wiseman_bd_session=' . $sid,
        CURLOPT_HTTPHEADER => ['User-Agent: Mozilla/5.0'],
    ]);
    $body = curl_exec($ch);
    $status = curl_getinfo($ch, CURLINFO_RESPONSE_CODE);
    $final = curl_getinfo($ch, CURLINFO_EFFECTIVE_URL);
    curl_close($ch);
    return [$status, $final, $body];
}

[$s, $f, $b] = get('http://localhost/Ecommerce1/admin/all_orders', $sid);
echo "all_orders => $s | $f\n";
echo (strpos($b, 'All Orders</h5>') !== false ? 'ALL ORDERS PAGE RENDERED' : 'NOT orders page') . "\n";
echo (strpos($b, 'Order Code') !== false ? 'has Order Code col' : 'no Order Code col') . "\n";
echo (strpos($b, 'login') !== false ? '!! login page shown' : '') . "\n\n";

[$s2, $f2, $b2] = get('http://localhost/Ecommerce1/admin/coupon', $sid);
echo "coupon => $s2 | $f2\n";
echo (strpos($b2, 'Coupon') !== false ? 'coupon page' : 'not coupon') . "\n";