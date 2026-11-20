<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosPurchaseReturn extends Model
{
    protected $guarded = [];
    protected $table = 'pos_purchase_returns';

    public function purchase()
    {
        return $this->belongsTo(PosPurchase::class, 'purchase_id');
    }

    public function supplier()
    {
        return $this->belongsTo(PosSupplier::class, 'supplier_id');
    }

    public function items()
    {
        return $this->hasMany(PosPurchaseReturnItem::class, 'purchase_return_id');
    }
}