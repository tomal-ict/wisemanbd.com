<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosSupplier extends Model
{
    protected $guarded = [];

    public function purchases()
    {
        return $this->hasMany(PosPurchase::class, 'supplier_id');
    }
}