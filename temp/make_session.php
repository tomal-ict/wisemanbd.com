<?php
use Illuminate\Support\Facades\Auth;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
set_error_handler(function ($no, $str) { return strpos($str, '${var}') !== false; });

$user = \App\Models\User::where('user_type', 'admin')->first();
if (!$user) { echo "NO ADMIN\n"; exit; }

Auth::login($user);
$app['session']->save();

echo "COOKIENAME=" . config('session.cookie') . "\n";
echo "SESSIONID=" . session()->getId() . "\n";