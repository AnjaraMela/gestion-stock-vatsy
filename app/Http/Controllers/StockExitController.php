<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\StockExit;
use App\Models\Store;

class StockExitController extends Controller
{ 
    public function index(Request $request)
    {
        $user = Auth::user();
        $query = StockExit::with(['product.store', 'store']);

        if ($user->role === 'admin') {
            // Admin peut filtrer par magasin
            if ($request->filled('store_id')) {
                $query->where('store_id', $request->store_id);
            }
            $stores = Store::all();
        } else {
            // Utilisateur simple ne voit que son propre magasin
            $query->where('store_id', $user->store_id);
            $stores = null;
        }

        $exits = $query->latest()->get();

        return view('stock_exits.index', compact('exits', 'stores'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $products = Product::with('store')->get(); // admin voit tout
            $stores = Store::all();
        } else {
            $products = Product::with('store')->where('store_id', $user->store_id)->get();
            $stores = null;
        }

        return view('stock_exits.create', compact('products', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'exit_date' => 'required|date',
            'reason' => 'nullable|string',
        ];

        if ($user->role === 'admin') {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        $validated = $request->validate($rules);

        StockExit::create([
            'product_id' => $validated['product_id'],
            'quantity' => $validated['quantity'],
            'exit_date' => $validated['exit_date'],
            'reason' => $validated['reason'] ?? null,
            'store_id' => $user->role === 'admin' ? $validated['store_id'] : $user->store_id,
        ]);

        return redirect()->route('stock_exits.index')->with('success', 'Sortie enregistrée.');
    }
    public function edit($id)
{
    $exit = StockExit::findOrFail($id);
    $user = Auth::user();

    // Vérifie si l'utilisateur a le droit de modifier cette sortie
    if ($user->role !== 'admin' && $exit->store_id !== $user->store_id) {
        abort(403, 'Accès refusé.');
    }

    $products = Product::query()
        ->when($user->role !== 'admin', fn($q) => $q->where('store_id', $user->store_id))
        ->get();

    $stores = Store::all(); // Affiché seulement si admin dans la vue

    return view('stock_exits.edit', compact('exit', 'products', 'stores'));
}

public function update(Request $request, $id)
{
    $exit = StockExit::findOrFail($id);
    $user = Auth::user();

    if ($user->role !== 'admin' && $exit->store_id !== $user->store_id) {
        abort(403, 'Accès refusé.');
    }

    $rules = [
        'quantity' => 'required|integer|min:1',
        'exit_date' => 'required|date',
        'reason' => 'nullable|string',
    ];

    if ($user->role === 'admin') {
        $rules['store_id'] = 'required|exists:stores,id';
    }

    $validated = $request->validate($rules);

    // Mise à jour
    $exit->update([
        'quantity' => $validated['quantity'],
        'exit_date' => $validated['exit_date'],
        'reason' => $validated['reason'] ?? null,
        'store_id' => $user->role === 'admin' ? $validated['store_id'] : $exit->store_id,
    ]);

    return redirect()->route('stock_exits.index')->with('success', 'Sortie mise à jour avec succès.');
}
public function destroy($id)
{
    $exit = StockExit::findOrFail($id);
    $user = Auth::user();

    if ($user->role !== 'admin' && $exit->store_id !== $user->store_id) {
        abort(403, 'Accès refusé.');
    }

    $exit->delete();

    return redirect()->route('stock_exits.index')->with('success', 'Sortie supprimée avec succès.');
}


public function confirmDelete($id)
{
    $exit = StockExit::with('product', 'store')->findOrFail($id);

    if (Auth::user()->role !== 'admin' && $exit->store_id !== Auth::user()->store_id) {
        abort(403, 'Accès refusé.');
    }

    return view('stock_exits.confirm_delete', compact('exit'));
}


}
