<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Store;
use App\Models\Product;
use App\Models\Sale;

class StoreController extends Controller
{
    public function index()
    {
        $stores = Store::all();
        return view('stores.index', compact('stores'));
    }

    public function create()
    {
        return view('stores.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        Store::create($request->all());

        return redirect()->route('stores.index')->with('success', 'Magasin ajouté avec succès.');
    }

    public function edit($id)
    {
        $store = Store::findOrFail($id);
        return view('stores.edit', compact('store'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'nullable|string|max:255',
        ]);

        $store = Store::findOrFail($id);
        $store->update($request->all());

        return redirect()->route('stores.index')->with('success', 'Magasin mis à jour.');
    }

    public function destroy($id)
    {
        $store = Store::findOrFail($id);
        $store->delete();

        return redirect()->route('stores.index')->with('success', 'Magasin supprimé.');
    }

    
}
