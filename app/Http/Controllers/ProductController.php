<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    { 
        $user = Auth::user();
        $query = Product::with('store');

    // Si l'utilisateur n'est pas admin, on filtre selon son magasin
    if ($user->role !== 'admin') {
        $query->where('store_id', $user->store_id);
    }

    // Recherche par marque ou modèle
    if ($request->has('search') && $request->search !== null) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('brand', 'like', '%' . $search . '%')
              ->orWhere('model', 'like', '%' . $search . '%');
        });
    }

    $products = $query->latest()->paginate(10);

    return view('products.index', compact('products','user' ));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $stores = Store::all(); // admin peut choisir le magasin
        } else {
            $stores = Store::where('id', $user->store_id)->get(); // utilisateur simple a un seul magasin
        }

        return view('products.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $products = Product::with('store')->get();


        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

        // Si utilisateur simple, forcer son store_id
        if ($user->role !== 'admin') {
            $validated['store_id'] = $user->store_id;
        }

        Product::create($validated);

        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    public function edit(Product $product)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->store_id !== $user->store_id) {
            abort(403); // Interdiction de modifier un produit d’un autre magasin
        }

        if ($user->role === 'admin') {
            $stores = Store::all();
        } else {
            $stores = Store::where('id', $user->store_id)->get();
        }

        return view('products.edit', compact('product', 'stores'));
    }

    public function update(Request $request, Product $product)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->store_id !== $user->store_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'brand' => 'required|string|max:255',
            'model' => 'required|string|max:255',
            'store_id' => 'required|exists:stores,id',
            'purchase_price' => 'required|numeric|min:0',
            'sale_price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
        ]);

       if ($user->role !== 'admin') {
             $validated['store_id'] = $user->store_id;
        }

        $product->update($validated);

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy(Product $product)
    {
        $user = Auth::user();

        if ($user->role !== 'admin' && $product->store_id !== $user->store_id) {
            abort(403);
        }

        $product->delete();

        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }
}
