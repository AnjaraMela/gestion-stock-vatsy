<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">📦 Liste des ventes</h1>

        {{-- Notifications --}}
        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        {{-- Boutons d'action --}}
        <div class="mb-3 d-flex justify-content-between">
            <a href="{{ route('sales.create') }}" class="btn btn-success">➕ Nouvelle vente</a>
            <a href="{{ route('sales.report') }}" class="btn btn-secondary">📊 Rapport</a>
        </div>

        {{-- Formulaire de recherche --}}
        <form method="GET" action="{{ route('sales.index') }}" class="row g-2 mb-4">
            <div class="col-md-4">
                <input type="text" name="search" class="form-control" placeholder="Rechercher produit ou marque" value="{{ request('search') }}">
            </div>

            @if(Auth::user()->role === 'admin')
                <div class="col-md-3">
                    <select name="store_id" class="form-select">
                        <option value="">-- Tous les magasins --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-3">
                <input type="date" name="date" class="form-control" value="{{ request('date') }}">
            </div>

            <div class="col-md-2">
                <button class="btn btn-outline-success w-100" type="submit">🔍 Rechercher</button>
            </div>
        </form>

        {{-- Tableau des ventes --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-danger">
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Magasin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($sales as $sale)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($sale->sale_date)->format('d/m/Y') }}</td>
                            <td>{{ $sale->product->name }} - {{ $sale->product->model }}</td>
                            <td>{{ $sale->quantity }}</td>
                            <td>{{ $sale->store->name }}</td>
                            <td>
                                @if(Auth::user()->role === 'admin' || Auth::user()->store_id === $sale->store_id)
                                    <a href="{{ route('sales.edit', $sale->id) }}" class="btn btn-sm btn-outline-success">✏️ Modifier</a>
                                    <form action="{{ route('sales.destroy', $sale->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cette vente ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">🗑️ Supprimer</button>
                                    </form>
                                @else
                                    <span class="text-muted">Accès restreint</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune vente trouvée.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
