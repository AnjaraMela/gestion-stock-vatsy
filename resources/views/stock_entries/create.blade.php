<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4 text-success">➕ Ajouter une entrée de stock</h1>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('stock_entries.store') }}">
            @csrf

            {{-- Produit --}}
            <div class="mb-3">
                <label for="product_id" class="form-label">Produit</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Sélectionner un produit --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} ({{ $product->brand }} - {{ $product->model }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required value="{{ old('quantity') }}">
            </div>

            {{-- Date d'entrée --}}
            <div class="mb-3">
                <label for="entry_date" class="form-label">Date d'entrée</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" required value="{{ old('entry_date', date('Y-m-d')) }}">
            </div>

            {{-- Magasin (visible uniquement pour l'admin, utilisé pour affichage clair) --}}
            @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label class="form-label">Magasin associé au produit</label>
                    <input type="text" class="form-control" value="{{ $products->firstWhere('id', old('product_id'))?->store->name ?? 'Sélectionnez un produit' }}" disabled>
                    <small class="text-muted">Le magasin est associé automatiquement au produit sélectionné.</small>
                </div>
            @endif

            <div class="text-end">
                <a href="{{ route('stock_entries.index') }}" class="btn btn-secondary">↩️ Annuler</a>
                <button type="submit" class="btn btn-success">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
