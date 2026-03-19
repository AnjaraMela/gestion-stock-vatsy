<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-5">

        <h1 class="mb-4 ">Modifier le téléphone</h1>
        <form action="{{ route('products.update', $product->id) }}" method="POST" class="bg-white p-4 rounded shadow">
            @csrf
            @method('PUT')

            @if(Auth::user()->isAdmin())
                <div class="mb-3">
                    <label for="store_id">Point de vente</label>
                    <select name="store_id" class="form-control" required>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $product->store_id == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="mb-3">
                <label>Nom</label>
                <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
            </div>
            <div class="mb-3">
                <label>Marque</label>
                <input type="text" name="brand" class="form-control" value="{{ $product->brand }}" required>
            </div>
            <div class="mb-3">
                <label>Modèle</label>
                <input type="text" name="model" class="form-control" value="{{ $product->model }}" required>
            </div>
            <div class="mb-3">
                <label>Prix d'achat</label>
                <input type="number" name="purchase_price" class="form-control" step="0.01" value="{{ $product->purchase_price }}" required>
            </div>
            <div class="mb-3">
                <label>Prix de vente</label>
                <input type="number" name="sale_price" class="form-control" step="0.01" value="{{ $product->sale_price }}" required>
            </div>

            <button type="submit" class="bg-yellow-500 text-white px-4 py-2 rounded">Mettre à jour</button>
            <a href="{{ route('products.index') }}" class="btn btn-secondary">↩️ Annuler</a>
        </form>
    </div>
</x-app-layout>
