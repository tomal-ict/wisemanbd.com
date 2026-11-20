<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosHold extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(PosCustomer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(PosHoldItem::class, 'hold_id');
    }
}