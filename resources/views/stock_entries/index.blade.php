<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des entrées de stock</h1>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
    {{-- Bouton Créer un arrivage --}}
        @if(auth()->user()->role === 'admin')
            <div class="mb-3">
                <a href="{{ route('stock_entries.create') }}" class="btn btn-success">
                    ➕ Ajouter un arrivage
                </a>
            </div>
        @endif

        {{-- Filtre par magasin (pour l'admin) --}}
        @if(auth()->user()->role === 'admin')
            <form method="GET" class="row g-3 mb-4">
                <div class="col-md-4">
                    <label for="store_id" class="form-label">Filtrer par magasin :</label>
                    <select name="store_id" id="store_id" class="form-select" onchange="this.form.submit()">
                        <option value="">-- Tous les magasins --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </form>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-danger text-white">
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Date d'entrée</th>
                        @if(auth()->user()->role === 'admin')
                            <th>Magasin</th>
                        @endif
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($arrivals as $entry)
                        <tr>
                            <td>{{ $entry->product->name ?? 'Produit supprimé' }}</td>
                            <td>{{ $entry->quantity }}</td>
                            <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d/m/Y') }}</td>
                            @if(auth()->user()->role === 'admin')
                                <td>{{ $entry->store->name ?? 'Magasin inconnu' }}</td>
                            @endif
                            <td>
                                @if(auth()->user()->role === 'admin' || auth()->user()->store_id === $entry->store_id)
                                    <a href="{{ route('stock_entries.edit', $entry->id) }}" class="btn btn-sm btn-outline-success">✏️Modifier</a>
                                    <form action="{{ route('stock_entries.destroy', $entry->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confirmer la suppression de cette entrée ?')">
                                        @csrf
                                        @method('DELETE')
                                        <button class="btn btn-sm btn-outline-danger">🗑️Supprimer</button>
                                    </form>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 5 : 4 }}" class="text-center">
                                Aucune entrée de stock trouvée.
                            </td>
                        </tr>
                    @endforelse
                    
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>