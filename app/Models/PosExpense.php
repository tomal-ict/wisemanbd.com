<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PosExpense extends Model
{
    protected $guarded = [];

    public function category()
    {
        return $this->belongsTo(PosExpenseCategory::class, 'category_id');
    }
}