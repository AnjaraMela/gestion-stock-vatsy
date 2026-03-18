<?php 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockEntry extends Model
{
    use HasFactory;
    protected $fillable = ['product_id','store_id','quantity', 'entry_date'];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
    public function store()
{
    return $this->belongsTo(Store::class);
}
}