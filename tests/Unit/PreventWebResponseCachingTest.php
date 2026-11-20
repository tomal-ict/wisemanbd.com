<?php

namespace Tests\Unit;

use App\Http\Middleware\PreventWebResponseCaching;
use Tests\TestCase;

class PreventWebResponseCachingTest extends TestCase
{
    public function test_web_responses_are_not_cacheable()
    {
        $response = $this->app->make(PreventWebResponseCaching::class)->handle(request(), function () {
            return response('OK');
        });

        $this->assertSame('no-cache, private, max-age=0, must-revalidate', $response->headers->get('Cache-Control'));
        $this->assertSame('no-cache', $response->headers->get('X-LiteSpeed-Cache-Control'));
    }
}
