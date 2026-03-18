<?php

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\SaleController;
use App\Http\Controllers\StockEntryController;
use App\Http\Controllers\StockController;
use App\Http\Controllers\StockExitController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\UserController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    $user = Auth::user();
    
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }

    return app(\App\Http\Controllers\DashboardController::class)->index();
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::resource('products', ProductController::class);
    // MAGASINS
    Route::resource('stores', StoreController::class)->except(['show']);


    // PRODUITS
   // Route::resource('products', ProductController::class); // Contient : index, create, store, edit, update, destroy, show

    // VENTES
    Route::resource('sales', SaleController::class)->except(['show']);;

    // Route séparée pour le rapport des ventes
    Route::get('/sales/report', [SaleController::class, 'report'])->name('sales.report');
    
    // ARRIVAGES (STOCK ENTRY)
    Route::resource('stock_entries', StockEntryController::class);

    // SORTIES DE STOCK
    Route::resource('stock_exits', StockExitController::class);

    // STOCK GLOBAL
    Route::resource('stock', StockController::class);

    Route::get('/stock_exits/{id}/confirm-delete', [StockExitController::class, 'confirmDelete'])
    ->name('stock_exits.confirmDelete');

});

// ----------------------------
// ROUTES AUTHENTIFIÉES
// ----------------------------
Route::middleware('auth','user')->group(function () {
    $user = Auth::user();

    // PROFIL UTILISATEUR
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /* PRODUITS
 //   Route::resource('products', ProductController::class);// Contient : index, create, store, edit, update, destroy, show
    //@dd($product[$user->id]);
    // VENTES
    Route::resource('sales', SaleController::class)->except(['show']);

    // Route séparée pour le rapport des ventes
    Route::get('/sales/report', [SaleController::class, 'report'])->name('sales.report');
    
    // ARRIVAGES (STOCK ENTRY)
    Route::resource('stock_entries', StockEntryController::class);

    // SORTIES DE STOCK
    Route::resource('stock_exits', StockExitController::class);

    // STOCK GLOBAL
    Route::resource('stock', StockController::class);*/
});

// ----------------------------
// ROUTES ADMIN UNIQUEMENT
// ----------------------------

Route::middleware(['auth', 'admin'])->group(function () {
    // PROFIL ADMIN
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


    //route du dashboard
    Route::get('/admin/dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');


   // GESTION DES UTILISATEURS (ADMIN UNIQUEMENT)
   Route::resource('/users', UserController::class, ['as' => 'admin'])->except(['show']);


    /* MAGASINS
    Route::resource('stores', StoreController::class)->except(['show']);


    // PRODUITS
   // Route::resource('products', ProductController::class); // Contient : index, create, store, edit, update, destroy, show

    // VENTES
    Route::resource('sales', SaleController::class)->except(['show']);;

    // Route séparée pour le rapport des ventes
    Route::get('/sales/report', [SaleController::class, 'report'])->name('sales.report');
    
    // ARRIVAGES (STOCK ENTRY)
    Route::resource('stock_entries', StockEntryController::class);

    // SORTIES DE STOCK
    Route::resource('stock_exits', StockExitController::class);

    // STOCK GLOBAL
    Route::resource('stock', StockController::class);

    Route::get('/stock_exits/{id}/confirm-delete', [StockExitController::class, 'confirmDelete'])
    ->name('stock_exits.confirmDelete');
*/

   
    
});

require __DIR__.'/auth.php';
