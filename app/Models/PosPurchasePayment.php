<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPurchasePayment extends Model
{
    protected $guarded = [];

    public function purchase()
    {
        return $this->belongsTo(PosPurchase::class, 'purchase_id');
    }
}