<x-app-layout>
    <div  class="max-w-2xl mx-auto px-4 py-5">
        <h2 class="mb-4 text-success">➖ Nouvelle sortie de stock</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stock_exits.store') }}" method="POST" class="bg-white p-4 rounded shadow">
            @csrf

            {{-- Produit --}}
            <div class="mb-3">
                <label for="product_id" class="form-label text-danger">Produit</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Sélectionner un produit --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->brand }} - {{ $product->model }}
                            ({{ $product->store->name ?? 'Magasin inconnu' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantity" class="form-label text-danger">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control" value="{{ old('quantity') }}" required min="1">
            </div>

            {{-- Date de sortie --}}
            <div class="mb-3">
                <label for="exit_date" class="form-label text-danger">Date de sortie</label>
                <input type="date" name="exit_date" id="exit_date" class="form-control" value="{{ old('exit_date') }}" required>
            </div>

            {{-- Raison (facultative) --}}
            <div class="mb-3">
                <label for="reason" class="form-label text-danger">Raison (facultative)</label>
                <input type="text" name="reason" id="reason" class="form-control" value="{{ old('reason') }}">
            </div>

            {{-- Magasin (admin uniquement) --}}
            @if (auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label for="store_id" class="form-label text-danger">Magasin</label>
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
                <input type="hidden" name="store_id" value="{{ auth()->user()->store_id }}">
            @endif


            {{-- Bouton --}}
            <div >
                
                <button type="submit" class="btn btn-danger">💾 Enregistrer la sortie</button>
                <a href="{{ route('stock_exits.index') }}" class="btn btn-secondary">↩️Retour</a>

            </div>
        </form>
    </div>
</x-app-layout>
