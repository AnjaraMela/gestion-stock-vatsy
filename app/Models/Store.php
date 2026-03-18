<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = ['name', 'location'];
    protected $table = 'stores';

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }

    public function stock_entries()
    {
        return $this->hasMany(StockEntry::class);
    }
    
    public function stock_Exits()
    {
        return $this->hasMany(StockExit::class);
    }
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
