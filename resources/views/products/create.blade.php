<x-app-layout>
    <div class="container mt-5">
        <h2 class="mb-4 text-success">➕ Ajouter un nouveau produit</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="name" class="form-label">Nom</label>
                <input type="text" name="name" id="name" class="form-control" value="{{ old('name') }}" required>
            </div>
            <div class="mb-3">
                <label for="brand" class="form-label">Marque</label>
                <input type="text" name="brand" id="brand" class="form-control" value="{{ old('brand') }}" required>
            </div>

            <div class="mb-3">
                <label for="model" class="form-label">Modèle</label>
                <input type="text" name="model" id="model" class="form-control" value="{{ old('model') }}" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Prix unitaire</label>
                <input type="number" name="purchase_price" id="purchase_price" class="form-control" value="{{ old('purchase_price') }}" required>
            </div>

            <div class="mb-3">
                <label for="price" class="form-label">Prix de vente</label>
                <input type="number" name="sale_price" id="sale_price" class="form-control" value="{{ old('sale_price') }}" required>
            </div>

            {{-- L'administrateur choisit le magasin du produit --}}
            @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label for="store_id" class="form-label">Magasin</label>
                    <select name="store_id" id="store_id" class="form-select" required>
                        <option value="">-- Sélectionner un magasin --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @else
                {{-- Si l'utilisateur est simple, le store_id est masqué et lié à son compte --}}
                <input type="hidden" name="store_id" value="{{ auth()->user()->store_id }}">
            @endif

            <button type="submit" class="btn btn-success">✅ Enregistrer</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">↩️ Annuler</a>
        </form>
    </div>
</x-app-layout>
