<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use App\Models\Sale;

class DashboardController extends Controller
{

public function index()
{
    $storeId = Auth::user()->store_id;

    // Statistiques globales
    $productsCount = Product::where('store_id', $storeId)->count();
    $stockEntriesCount = StockEntry::where('store_id', $storeId)->count();
    $stockExitsCount = StockExit::where('store_id', $storeId)->count();
    $salesCount = Sale::where('store_id', $storeId)->count();

    // Données de ventes par jour (7 derniers jours)
    $salesByDay = Sale::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('COUNT(*) as total')
        )
        ->where('store_id', $storeId)
        ->whereBetween('created_at', [now()->subDays(6), now()])
        ->groupBy('date')
        ->orderBy('date')
        ->get();

    $labels = $salesByDay->pluck('date');
    $data = $salesByDay->pluck('total');

    return view('dashboard', compact(
        'productsCount',
        'stockEntriesCount',
        'stockExitsCount',
        'salesCount',
        'labels',
        'data'
    ));
}

}
