<?php

namespace Tests\Unit;

use App\Http\Middleware\EnsurePersistentSession;
use Illuminate\Session\FileSessionHandler;
use Tests\TestCase;

class EnsurePersistentSessionTest extends TestCase
{
    public function test_array_sessions_fall_back_to_file_sessions_for_web_requests()
    {
        $environment = $this->app['env'];
        $driver = config('session.driver');
        $files = config('session.files');

        try {
            $this->app['env'] = 'local';
            config(['session.driver' => 'array']);
            app('session')->driver();

            $this->app->make(EnsurePersistentSession::class)->handle(request(), function () {
                return response();
            });

            $this->assertSame('file', config('session.driver'));
            $this->assertSame(storage_path('framework/sessions'), config('session.files'));
            $this->assertInstanceOf(FileSessionHandler::class, app('session')->driver()->getHandler());
        } finally {
            $this->app['env'] = $environment;
            config([
                'session.driver' => $driver,
                'session.files' => $files,
            ]);
            app('session')->forgetDrivers();
        }
    }
}
