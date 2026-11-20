<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and is
| the IoC container for the system binding all of the various parts.
|
*/

// Make a copied source tree usable before Laravel loads its cached configuration.
$basePath = realpath(__DIR__.'/../');
$environmentPath = $basePath.DIRECTORY_SEPARATOR.'.env';
$environmentExamplePath = $basePath.DIRECTORY_SEPARATOR.'.env.example';

if (! is_file($environmentPath) && is_file($environmentExamplePath)) {
    @copy($environmentExamplePath, $environmentPath);
}

if (is_file($environmentPath) && is_writable($environmentPath)) {
    $environment = file_get_contents($environmentPath);
    preg_match('/^APP_KEY=(.*)$/m', $environment, $appKeyMatch);
    $appKey = isset($appKeyMatch[1]) ? trim($appKeyMatch[1], " \t\n\r\0\x0B\"'") : '';

    if ($appKey === '' || $appKey === 'base64:') {
        $newAppKey = 'base64:'.base64_encode(random_bytes(32));
        $environment = preg_replace('/^APP_KEY=.*$/m', 'APP_KEY='.$newAppKey, $environment, 1, $replacements);

        if ($replacements === 0) {
            $environment .= PHP_EOL.'APP_KEY='.$newAppKey.PHP_EOL;
        }

        @file_put_contents($environmentPath, $environment);
    }
}

foreach ([
    $basePath.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache',
    $basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'data',
    $basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'sessions',
    $basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'framework'.DIRECTORY_SEPARATOR.'views',
    $basePath.DIRECTORY_SEPARATOR.'storage'.DIRECTORY_SEPARATOR.'logs',
] as $directory) {
    if (! is_dir($directory)) {
        @mkdir($directory, 0775, true);
    }
}

$cachedConfigPath = $basePath.DIRECTORY_SEPARATOR.'bootstrap'.DIRECTORY_SEPARATOR.'cache'.DIRECTORY_SEPARATOR.'config.php';

if (is_file($cachedConfigPath)) {
    $cachedConfig = @include $cachedConfigPath;
    $cachedSessionPath = is_array($cachedConfig) ? ($cachedConfig['session']['files'] ?? null) : null;
    $cachedViewPath = is_array($cachedConfig) ? ($cachedConfig['view']['paths'][0] ?? null) : null;
    $environmentWasUpdated = is_file($environmentPath) && filemtime($cachedConfigPath) < filemtime($environmentPath);

    if ($environmentWasUpdated || ($cachedSessionPath && ! is_dir($cachedSessionPath)) || ($cachedViewPath && ! is_dir($cachedViewPath))) {
        @unlink($cachedConfigPath);
    }
}

$app = new Illuminate\Foundation\Application($basePath);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| incoming requests to this application from both the web and CLI.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

return $app;
