<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\User;
use App\Models\Store;
use App\Models\StockEntry;
use App\Models\StockExit;
use App\Models\Sale;
use Carbon\Carbon;



class AdminDashboardController extends Controller{

    public function index(){

        $totalUsers = User::count();
        $totalProducts = Product::count();
        $totalSales = Sale::sum('quantity');

        $stockIn = StockEntry::sum('quantity');
        $stockOut = StockExit::sum('quantity');
        $stockSold = Sale::sum('quantity');
        $stockTotal = $stockIn - $stockOut - $stockSold;

        // Ventes groupées par mois
        $salesPerMonth = DB::table('sales')
            ->selectRaw('MONTH(sale_date) as month, COUNT(*) as count')
            ->groupBy(DB::raw('MONTH(sale_date)'))
            ->pluck('count', 'month');

        // Préparer les données pour le graphique
        $labels = [];
        $data = [];

        foreach (range(1, 12) as $month) {
            $labels[] = Carbon::create()->month($month)->format('F');
            $data[] = $salesPerMonth->get($month, 0);
        }

        
        return view('admin.dashboard' , compact(
            'totalUsers',
            'totalProducts',
            'totalSales',
            'stockIn',
            'stockOut',
            'stockTotal',
            'labels',
            'data'
        ));
    }
}