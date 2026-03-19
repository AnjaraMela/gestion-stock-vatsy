<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'brand', 'model','store_id', 'purchase_price', 'sale_price'];

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
   
    public function stockEntries()
    {
        return $this->hasMany(StockEntry::class);
       
    }

    public function stockExits()
    {
        return $this->hasMany(StockExit::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
    //DEBUT DU MODIF
    public function realStock($storeId = null)
{
    $in = $this->stockEntries()
        ->when($storeId, fn($q) => $q->where('store_id', $storeId))
        ->sum('quantity');

    $out = $this->stockExits()
        ->when($storeId, fn($q) => $q->where('store_id', $storeId))
        ->sum('quantity');

    $sold = $this->sales()
        ->when($storeId, fn($q) => $q->where('store_id', $storeId))
        ->sum('quantity');

    return $in - $out - $sold;
}

}
