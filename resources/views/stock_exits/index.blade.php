<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">📦 Sorties de Stock</h1>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif
        <div class="mb-3">
            <a href="{{ route('stock_exits.create') }}" class="btn btn-success">
                ➕ Nouvelle sortie
            </a>
        </div>

        {{-- Filtres pour l’administrateur --}}
        @if(auth()->user()->role === 'admin')
            <form method="GET" class="row g-3 align-items-end mb-3">
                <div class="col-md-4">
                    <label for="store_id" class="form-label text-danger">Filtrer par magasin</label>
                    <select name="store_id" id="store_id" class="form-select">
                        <option value="">-- Tous les magasins --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ request('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-outline-success">Filtrer</button>
                </div>
            </form>
        @endif

        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-danger text-white">
                    <tr>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Date de sortie</th>
                        <th>Raison</th>
                        <th>Magasin</th>
                        <th class="text-center">Actions</th>

                    </tr>
                </thead>
                <tbody>
                    @forelse ($exits as $exit)
                        <tr>
                            {{-- Produit --}}
                            <td class="text-dark">
                                @if ($exit->product)
                                    {{ $exit->product->name }} - {{ $exit->product->brand }} - {{ $exit->product->model }}
                                @else
                                    <span class="text-danger">Produit supprimé</span>
                                @endif
                            </td>

                            {{-- Quantité --}}
                            <td class="text-dark">{{ $exit->quantity }}</td>

                            {{-- Date --}}
                            <td class="text-dark">
                                {{ \Carbon\Carbon::parse($exit->exit_date)->format('d/m/Y') }}
                            </td>

                            {{-- Raison --}}
                            <td class="text-dark">{{ $exit->reason ?? 'N/A' }}</td>

                            {{-- Magasin --}}
                            <td class="text-dark">
                                @if ($exit->store)
                                    {{ $exit->store->name }}
                                @elseif ($exit->product && $exit->product->store)
                                    {{ $exit->product->store->name }}
                                @else
                                    <span class="text-danger">Inconnu</span>
                                @endif
                            </td>
                        
                        <td class="text-center">
                            <a href="{{ route('stock_exits.edit', $exit->id) }}" class="btn btn-sm btn-outline-success">
                                ✏️ Modifier
                            </a>

                            <form action="{{ route('stock_exits.destroy', $exit->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette sortie ?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Supprimer</button>
                            </form>
                        </td>
                        </tr>
                    
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Aucune sortie enregistrée.</td>
                        </tr>
                        
                    @endforelse

                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
