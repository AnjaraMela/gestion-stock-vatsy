<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">📦 Gestion de Stock</h1>

        <!-- Filtres -->
        <form method="GET" action="{{ route('stock.index') }}" class="row g-3 mb-4 align-items-end">
            @if(auth()->user()->role === 'admin')
                <div class="col-md-3">
                    <label for="store_id" class="form-label">Filtrer par magasin</label>
                    <select name="store_id" id="store_id" class="form-select">
                        <option value="">Tous les magasins</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-3">
                <label for="start_date" class="form-label">Date début</label>
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>

            <div class="col-md-3">
                <label for="end_date" class="form-label">Date fin</label>
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>

            <div class="col-md-3">
                <label for="search" class="form-label">Recherche (nom, marque, modèle)</label>
                <input type="text" name="search" class="form-control" placeholder="Rechercher..." value="{{ request('search') }}">
            </div>

            <div class="col-12 text-end">
                <button type="submit" class="btn btn-outline-success">🔍 Filtrer</button>
                <a href="{{ route('stock.index') }}" class="btn btn-secondary">❌ Réinitialiser</a>
            </div>
        </form>

        <!-- Tableau des stocks -->
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead class="table-danger text-white">
                    <tr>
                        <th>Nom</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <th>Magasin</th>
                        <th>Stock restant</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($stockData as $item)
                        <tr>
                            <td>{{ $item['product']->name }}</td>
                            <td>{{ $item['product']->brand }}</td>
                            <td>{{ $item['product']->model }}</td>
                            <td>{{ $item['product']->store->name ?? 'Aucun' }}</td>
                            <td>
                                <span class="badge bg-{{ $item['stock'] > 0 ? 'success' : 'danger' }}">
                                    {{ $item['stock'] }}
                                </span>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center">Aucun produit trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Total stock -->
        <div class="mt-4 text-end">
            <h5>Total global du stock : <span class="badge bg-info">{{ $totalStock }}</span></h5>
        </div>
    </div>
</x-app-layout>
