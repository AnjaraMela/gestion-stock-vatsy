<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Sale;
use App\Models\Store;
use App\Models\Product;

class SaleController extends Controller
{
    public function index(Request $request)
{
    $user = Auth::user();
    $salesQuery = Sale::with('product', 'store')->latest();

    if ($user->role !== 'admin') {
        $salesQuery->where('store_id', $user->store_id);
    } else if ($request->filled('store_id')) {
        $salesQuery->where('store_id', $request->store_id);
    }

    if ($request->filled('search')) {
        $salesQuery->whereHas('product', function ($query) use ($request) {
            $query->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('brand', 'like', '%' . $request->search . '%');
        });
    }

    if ($request->filled('date')) {
        $salesQuery->whereDate('sale_date', $request->date);
    }

    $sales = $salesQuery->get();
    $products = Product::all();
    $stores = Store::all();

    return view('sales.index', compact('sales', 'products', 'stores'));
}


    public function create()
    {
        $storeId = Auth::user()->role === 'admin' ? null : Auth::user()->store_id;

        $productsQuery = Product::query();
        if ($storeId) {
            $productsQuery->where('store_id', $storeId);
        }

        $products = $productsQuery->get()->map(function ($product) use ($storeId) {
            $product->real_stock = $product->realStock($storeId);
            return $product;
        });

        $stores = Store::all();

        return view('sales.create', compact('products', 'stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'sale_date' => 'required|date',
        ];

        if ($user->role === 'admin') {
            $rules['store_id'] = 'required|exists:stores,id';
        }

        $request->validate($rules);

        $storeId = $user->role === 'admin' ? $request->store_id : $user->store_id;

        $product = Product::findOrFail($request->product_id);

        $currentStock = $product->realStock($storeId);

        if ($currentStock < $request->quantity) {
            return back()->with('error', "Stock insuffisant : seulement $currentStock en stock.");
        }

        Sale::create([
            'product_id' => $request->product_id,
            'quantity' => $request->quantity,
            'sale_date' => $request->sale_date,
            'store_id' => $storeId,
        ]);

        return redirect()->route('sales.index')->with('success', 'Vente enregistrée avec succès.');
    }

    public function report(Request $request)
    {
        $user = Auth::user();

        $query = Sale::selectRaw('product_id, store_id, sale_date, SUM(quantity) as total_quantity')
            ->groupBy('product_id', 'store_id', 'sale_date')
            ->with(['product', 'store']);

        if ($user->role !== 'admin') {
            $query->where('store_id', $user->store_id);
        } else {
            if ($request->filled('store_id')) {
                $query->where('store_id', $request->store_id);
            }
        }

        if ($request->filled('product_id')) {
            $query->where('product_id', $request->product_id);
        }

        if ($request->filled('start_date')) {
            $query->whereDate('sale_date', '>=', $request->start_date);
        }
        if ($request->filled('end_date')) {
            $query->whereDate('sale_date', '<=', $request->end_date);
        }

        $report = $query->get()->map(function ($item) {
            $item->total_revenue = $item->product->sale_price * $item->total_quantity;
            return $item;
        });

        $totalSales = $report->sum('total_quantity');
        $totalRevenue = $report->sum('total_revenue');

        $products = Product::all();
        $stores = Store::all();

        return view('sales.report', compact('report', 'totalSales', 'totalRevenue', 'products', 'stores'))
            ->with([
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'selected_product' => $request->product_id,
                'selected_store' => $request->store_id,
            ]);
    }
    public function edit($id)
{
    $sale = Sale::findOrFail($id);
    $user = Auth::user();

    // Vérification des autorisations
    if ($user->role !== 'admin' && $sale->store_id !== $user->store_id) {
        abort(403, 'Accès refusé.');
    }

    // Récupération des produits visibles selon le rôle
    $productsQuery = Product::query();
    if ($user->role !== 'admin') {
        $productsQuery->where('store_id', $user->store_id);
    }
    $products = $productsQuery->get();

    // Liste des magasins (admin uniquement)
    $stores = Store::all();

    return view('sales.edit', compact('sale', 'products', 'stores'));
}

public function update(Request $request, $id)
{
    $sale = Sale::findOrFail($id);
    $user = Auth::user();

    // Autorisation : un utilisateur simple ne peut modifier que ses propres ventes
    if ($user->role !== 'admin' && $sale->store_id !== $user->store_id) {
        abort(403, 'Accès refusé.');
    }

    // Validation
    $rules = [
        'product_id' => 'required|exists:products,id',
        'quantity' => 'required|integer|min:1',
        'sale_date' => 'required|date',
    ];

    if ($user->role === 'admin') {
        $rules['store_id'] = 'required|exists:stores,id';
    }

    $request->validate($rules);

    // Déterminer le magasin
    $storeId = $user->role === 'admin' ? $request->store_id : $user->store_id;

    // Récupérer le produit
    $product = Product::findOrFail($request->product_id);

    // Calcul du stock réel disponible (en ajoutant temporairement l'ancienne quantité de cette vente)
    $currentStock = $product->realStock($storeId) + $sale->quantity;

    if ($request->quantity > $currentStock) {
        return back()->with('error', "Stock insuffisant : seulement $currentStock disponible.");
    }

    // Mise à jour de la vente
    $sale->update([
        'product_id' => $request->product_id,
        'quantity' => $request->quantity,
        'sale_date' => $request->sale_date,
        'store_id' => $storeId,
    ]);

    return redirect()->route('sales.index')->with('success', 'Vente mise à jour avec succès.');
}

    public function destroy($id)
    {
        $sale = Sale::findOrFail($id);

        if (Auth::user()->role !== 'admin' && $sale->store_id !== Auth::user()->store_id) {
            abort(403, 'Accès refusé.');
        }

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Vente supprimée avec succès.');
    }
}
