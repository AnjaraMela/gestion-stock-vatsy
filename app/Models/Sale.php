<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'sale_date',
        'store_id',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }


}
