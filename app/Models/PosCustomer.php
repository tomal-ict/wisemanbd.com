<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosCustomer extends Model
{
    protected $guarded = [];

    public function sales()
    {
        return $this->hasMany(PosSale::class, 'customer_id');
    }
}