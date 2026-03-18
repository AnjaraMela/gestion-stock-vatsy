<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-success">➕ Nouvelle Vente</h2>

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('sales.store') }}">
            @csrf

            <div class="mb-3">
                <label for="product_id" class="form-label">Produit</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Sélectionner un produit --</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}">
                            {{ $product->name }} {{ $product->model }} (Stock : {{ $product->real_stock }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="quantity" class="form-label">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required min="1">
            </div>

            <div class="mb-3">
                <label for="sale_date" class="form-label">Date de la vente</label>
                <input type="date" name="sale_date" id="sale_date" class="form-control" required>
            </div>

            @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label for="store_id" class="form-label">Magasin</label>
                    <select name="store_id" class="form-select" required>
                        <option value="">-- Choisir un magasin --</option>
                        @foreach($stores as $store)
                            <option value="{{ $store->id }}">{{ $store->name }}</option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="text-end">
                <a href="{{ route('sales.index') }}" class="btn btn-secondary">↩️ Annuler</a>
                <button type="submit" class="btn btn-success">💾 Enregistrer</button>
            </div>
        </form>
    </div>
</x-app-layout>
