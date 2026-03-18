<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-primary">✏️ Modifier une vente</h2>

        {{-- Affichage des erreurs --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        {{-- Affichage d'un message d'erreur stock --}}
        @if (session('error'))
            <div class="alert alert-warning">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('sales.update', $sale->id) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- Produit --}}
            <div class="mb-3">
                <label for="product_id" class="form-label text-primary">Produit</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Choisir un produit --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}"
                            {{ old('product_id', $sale->product_id) == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->brand }} - {{ $product->model }}
                            (Stock : {{ $product->realStock(auth()->user()->role === 'admin' ? old('store_id', $sale->store_id) : auth()->user()->store_id) }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantity" class="form-label text-primary">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                    value="{{ old('quantity', $sale->quantity) }}" required min="1">
            </div>

            {{-- Date de vente --}}
            <div class="mb-3">
                <label for="sale_date" class="form-label text-primary">Date de vente</label>
                <input type="date" name="sale_date" id="sale_date" class="form-control"
                    value="{{ old('sale_date',\Carbon\Carbon::parse( $sale->sale_date)->format('Y-m-d')) }}" required>
            </div>

            {{-- Magasin (admin uniquement) --}}
            @if (auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label for="store_id" class="form-label text-primary">Magasin</label>
                    <select name="store_id" id="store_id" class="form-select" required>
                        <option value="">-- Sélectionner un magasin --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}"
                                {{ old('store_id', $sale->store_id) == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Boutons --}}
            <div class="text-end">
                <a href="{{ route('sales.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
            </div>
        </form>
    </div>
</x-app-layout>
