<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LandingPage extends Model
{
    protected $fillable = [
        'product_id',
        'title',
        'discount',
        'start_date',
        'end_date',
        'header_text',
        'footer_text',
        'description',
        'status'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}