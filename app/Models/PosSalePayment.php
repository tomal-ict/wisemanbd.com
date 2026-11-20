<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSalePayment extends Model
{
    protected $guarded = [];

    public function sale()
    {
        return $this->belongsTo(PosSale::class, 'sale_id');
    }
}