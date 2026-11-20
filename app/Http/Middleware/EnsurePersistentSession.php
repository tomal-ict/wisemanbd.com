<?php

namespace App\Http\Middleware;

use Closure;

class EnsurePersistentSession
{
    public function handle($request, Closure $next)
    {
        // Array sessions are request-only and make CSRF validation fail on real web requests.
        $updates = [];

        if (! app()->runningUnitTests() && config('session.driver') === 'array') {
            $updates['session.driver'] = 'file';
        }

        if (($updates['session.driver'] ?? config('session.driver')) === 'file') {
            $sessionPath = storage_path('framework/sessions');

            if (! is_dir($sessionPath)) {
                @mkdir($sessionPath, 0775, true);
            }

            // Some cPanel accounts cannot write the copied storage directory.
            // Use PHP's writable temporary directory rather than losing sessions.
            if (! is_writable($sessionPath)) {
                $sessionPath = rtrim(sys_get_temp_dir(), DIRECTORY_SEPARATOR)
                    .DIRECTORY_SEPARATOR.'laravel-sessions-'.sha1(base_path());

                if (! is_dir($sessionPath)) {
                    @mkdir($sessionPath, 0775, true);
                }
            }

            if (! is_writable($sessionPath)) {
                throw new \RuntimeException('The Laravel session directory is not writable. Set storage/framework/sessions to permission 775.');
            }

            if (config('session.files') !== $sessionPath) {
                $updates['session.files'] = $sessionPath;
            }
        }

        if ($updates) {
            config($updates);
            app('session')->forgetDrivers();
        }

        return $next($request);
    }
}
