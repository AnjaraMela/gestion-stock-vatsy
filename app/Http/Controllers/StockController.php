<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\StockEntry;
use App\Models\StockExit;
use App\Models\Sale;
use App\Models\Store;

class StockController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $search = $request->input('search');
        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');

        // Base de la requête
        $query = Product::with('store');

        // Filtrer par magasin (admin)
        if ($user->role === 'admin') {
            if ($request->filled('store_id')) {
                $query->where('store_id', $request->store_id);
            }
        } else {
            $query->where('store_id', $user->store_id);
        }

        // Recherche texte
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('brand', 'like', "%$search%")
                  ->orWhere('model', 'like', "%$search%");
            });
        }

        $products = $query->get();

        // Calcul du stock
        $stockData = $products->map(function ($product) use ($startDate, $endDate) {
            $entries = $product->stockEntries();
            $sales = $product->sales();
            $exits = $product->stockExits();

            if ($startDate && $endDate) {
                $entries->whereBetween('entry_date', [$startDate, $endDate]);
                $sales->whereBetween('sale_date', [$startDate, $endDate]);
                $exits->whereBetween('exit_date', [$startDate, $endDate]);
            }

            $totalIn = $entries->sum('quantity');
            $totalSold = $sales->sum('quantity');
            $totalOut = $exits->sum('quantity');

            return [
                'product' => $product,
                'stock' => $totalIn - $totalSold - $totalOut,
            ];
        });

        $totalStock = $stockData->sum('stock');

        // Liste des magasins (pour admin seulement)
        $stores = $user->role === 'admin' ? Store::all() : null;

        return view('stock.index', compact(
            'stockData',
            'totalStock',
            'stores'
        ));
    }
}
