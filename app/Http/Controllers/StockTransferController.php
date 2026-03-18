<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Store;

class StockTransferController extends Controller
{
    public function create()
{
    return view('transfers.create', [
        'products' => \App\Models\Product::all(),
        'stores' => \App\Models\Store::all()
    ]);
}

public function store(Request $request)
{
    $data = $request->validate([
        'product_id' => 'required|exists:products,id',
        'from_store_id' => 'required|exists:stores,id',
        'to_store_id' => 'required|exists:stores,id|different:from_store_id',
        'quantity' => 'required|integer|min:1'
    ]);

    $product = Product::where('id', $data['product_id'])
        ->where('store_id', $data['from_store_id'])
        ->firstOrFail();

    if ($product->stock < $data['quantity']) {
        return back()->withErrors(['stock' => 'Stock insuffisant pour ce transfert']);
    }

    // Diminuer le stock du magasin source
    $product->decrement('stock', $data['quantity']);

    // Augmenter ou créer le produit dans le magasin destination
    $targetProduct = Product::firstOrCreate([
        'imei' => $product->imei,
        'store_id' => $data['to_store_id']
    ], [
        'name' => $product->name,
        'brand' => $product->brand,
        'model' => $product->model,
        'purchase_price' => $product->purchase_price,
        'sale_price' => $product->sale_price,
        'stock' => 0
    ]);

    $targetProduct->increment('stock', $data['quantity']);

    StockTransfer::create($data);

    return redirect()->route('transfers.create')->with('success', 'Transfert effectué avec succès.');
}

}
