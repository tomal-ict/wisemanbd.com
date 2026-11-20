<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosReturn extends Model
{
    protected $guarded = [];
    protected $table = 'pos_returns';

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'sale_id');
    }

    public function customer()
    {
        return $this->belongsTo(PosCustomer::class, 'customer_id');
    }

    public function items()
    {
        return $this->hasMany(PosReturnItem::class, 'return_id');
    }
}