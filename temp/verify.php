<?php
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

$sid = $argv[1];

[$s, $f, $b] = get('http://localhost/Ecommerce1/admin/', $sid);
echo "admin/  => $s | $f\n";
echo (strpos($b, 'Dashboard') !== false ? 'DASHBOARD OK' : 'no dashboard') . "\n";
preg_match('/href="([^"]*all_orders[^"]*)"/', $b, $m);
echo "All-Orders link: " . ($m[1] ?? 'NONE') . "\n";
preg_match('/href="([^"]*coupon[^"]*)"/', $b, $m2);
echo "Coupon link: " . ($m2[1] ?? 'NONE') . "\n";

[$s2, $f2, $b2] = get('http://localhost/Ecommerce1/admin/all_orders', $sid);
echo "\nall_orders => $s2 | $f2\n";
echo (strpos($b2, 'All Orders</h5>') !== false ? '=> ORDERS PAGE OK' : '=> NOT THE ORDERS PAGE') . "\n";
[$s3, $f3, $b3] = get('http://localhost/Ecommerce1/admin/coupon', $sid);
echo "coupon    => $s3 | $f3\n";
echo (strpos($b3, 'Coupon') !== false ? '=> COUPON(MARKETING) PAGE' : '=> ?') . "\n";