<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class BusinessSetting extends Model
{
    protected static function boot()
    {
        parent::boot();

        static::saved(function () {
            Cache::forget('business_settings');
        });
    }
}
