<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des produits</h1>

        {{-- Message de succès --}}
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        {{-- Bouton Créer un produit --}}
        @if(auth()->user()->role === 'admin')
            <div class="mb-3">
                <a href="{{ route('products.create') }}" class="btn btn-success">
                    ➕ Ajouter un produit
                </a>
            </div>
        @endif

        {{-- Formulaire de recherche --}}
        <form method="GET" action="{{ route('products.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" class="form-control" placeholder="Recherche par marque ou modèle..."
                       value="{{ request('search') }}">
                <button class="btn btn-outline-success" type="submit">🔍 Rechercher</button>
            </div>
        </form>

        {{-- Tableau des produits --}}
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead class="table-danger text-white">
                    <tr>
                        <th>Nom</th>
                        <th>Marque</th>
                        <th>Réference</th>
                        <th>Prix Unitaire</th>
                        <th>Prix de vente</th>
                        <th>Magasin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->brand }}</td>
                            <td>{{ $product->model }}</td>
                            <td>{{ number_format($product->purchase_price, 0, ',', ' ') }} Ar</td>
                            <td>{{ number_format($product->sale_price, 0, ',', ' ') }} Ar</td>
                            <td>{{ $product->store->name ?? 'Aucun' }}</td>
                            <td>
                                <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-outline-success">
                                    ✏️ Modifier
                                </a>
                                <form action="{{ route('products.destroy', $product) }}" method="POST"
                                      class="d-inline" onsubmit="return confirm('Confirmer la suppression ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit">
                                        🗑️ Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Aucun produit trouvé.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
