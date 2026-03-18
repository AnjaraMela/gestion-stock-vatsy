<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\Store;

class StockEntryController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        // Admin : peut filtrer par magasin
        if ($user->role === 'admin') {
            $storeId = $request->input('store_id');
            $arrivals = StockEntry::with('product', 'store')
                        ->when($storeId, fn($query) => $query->where('store_id', $storeId))
                        ->latest()
                        ->get();

            $stores = Store::all();
        } else {
            // Utilisateur simple : ne voit que son propre magasin
            $storeId = $user->store_id;
            $arrivals = StockEntry::with('product')
                        ->where('store_id', $storeId)
                        ->latest()
                        ->get();

            $stores = null;
        }

        return view('stock_entries.index', compact('arrivals', 'stores', 'storeId'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $products = Product::with('store')->get();
            $stores = Store::all();
        } else {
            $products = Product::where('store_id', $user->store_id)->get();
            $stores = null;
        }

        return view('stock_entries.create', compact('products', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'entry_date' => 'required|date',
        ]);

        // Pour les admins, store_id est récupéré depuis le produit lié
        $product = Product::findOrFail($validated['product_id']);

        $storeId = $user->role === 'admin'
            ? $product->store_id
            : $user->store_id;

        StockEntry::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'entry_date' => $validated['entry_date'],
            'store_id' => $storeId,
        ]);

        return redirect()->route('stock_entries.index')->with('success', 'Entrée enregistrée.');
    }
    public function edit($id)
{
    $stockEntry = StockEntry::with('product.store')->findOrFail($id);

    // Accès limité si utilisateur simple
    if (auth()->user()->role !== 'admin' && $stockEntry->store_id !== auth()->user()->store_id) {
        abort(403);
    }

    return view('stock_entries.edit', compact('stockEntry'));
}

public function update(Request $request, $id)
{
    $stockEntry = StockEntry::findOrFail($id);

    // Sécurité pour utilisateurs simples
    if (auth()->user()->role !== 'admin' && $stockEntry->store_id !== auth()->user()->store_id) {
        abort(403);
    }

    $request->validate([
        'quantity' => 'required|integer|min:1',
        'entry_date' => 'required|date',
    ]);

    $stockEntry->update([
        'quantity' => $request->quantity,
        'entry_date' => $request->entry_date,
    ]);

    return redirect()->route('stock_entries.index')->with('success', 'Entrée de stock mise à jour.');
}
public function destroy($id)
{
    $stockEntry = StockEntry::findOrFail($id);

    if (auth()->user()->role !== 'admin' && $stockEntry->store_id !== auth()->user()->store_id) {
        abort(403);
    }

    $stockEntry->delete();

    return redirect()->route('stock_entries.index')->with('success', 'Entrée de stock supprimée.');
}


}
