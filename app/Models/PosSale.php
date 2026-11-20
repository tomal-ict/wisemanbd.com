<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSale extends Model
{
    protected $guarded = [];

    public function customer()
    {
        return $this->belongsTo(PosCustomer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(PosSaleItem::class, 'sale_id');
    }

    public function payments()
    {
        return $this->hasMany(PosSalePayment::class, 'sale_id');
    }
}