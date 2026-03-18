<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with('store')->get(); // Pour afficher le nom du magasin dans la vue
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $user = Auth::user();

        if ($user->role === 'admin') {
            $stores = Store::all(); // admin peut choisir le magasin
        }
        return view('admin.users.create', compact('stores'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'role' => 'required|in:admin,user',
            'store_id' => 'nullable|exists:stores,id',
            'password' => 'required|confirmed|min:6',
    ]);

    $storeId = $request->role === 'user' ? $request->store_id : null;

    if ($request->role === 'user' && !$storeId) {
        return back()->withErrors(['store_id' => 'Le magasin est requis pour les utilisateurs simples.'])->withInput();
    }

    User::create([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'store_id' => $storeId,
        'password' => bcrypt($request->password),
    ]);

    return redirect()->route('admin.users.index')->with('success', 'Utilisateur créé avec succès.');
}
public function edit($id){
   $user = Auth::user(); 
   
  // $id = $user->id;
   
    if ($user->role === 'admin') {
        $user = User::find($id);
        $stores = Store::all();
    }else{
        $id = $user->id;
    }
    /*
    if ($user->role === 'admin') {
        $user = User::find($id);
        $user = user::all();
        $stores = Store::all();  
    }*/

    return view('admin.users.edit', compact('user','stores','id'));
}

public function update(Request $request, User $user)
{
    
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:admin,user',
        'store_id' => 'nullable|exists:stores,id',
    ]);

    $storeId = $request->role === 'user' ? $request->store_id : null;

    if ($request->role === 'user' && !$storeId) {
        return back()
            ->withErrors(['store_id' => 'Le magasin est requis pour les utilisateurs simples.'])
            ->withInput();
    }

    $user->update([
        'name' => $request->name,
        'email' => $request->email,
        'role' => $request->role,
        'store_id' => $storeId,
    ]);

    return redirect()->route('admin.users.index')
        ->with('success', 'Utilisateur mis à jour.');
}

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Utilisateur supprimé.');
    }
}
