<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPurchase extends Model
{
    protected $guarded = [];

    public function supplier()
    {
        return $this->belongsTo(PosSupplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PosPurchaseItem::class, 'purchase_id');
    }

    public function payments()
    {
        return $this->hasMany(PosPurchasePayment::class, 'purchase_id');
    }
}