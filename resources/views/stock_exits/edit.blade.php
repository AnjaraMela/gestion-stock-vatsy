<x-app-layout>
    <div class="max-w-2xl mx-auto px-4 py-5">
        <h2 class="mb-4 text-success">✏️ Modifier la sortie de stock</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('stock_exits.update', $exit->id) }}" method="POST" class="bg-white p-4 rounded shadow">
            @csrf
            @method('PUT')

            {{-- Produit --}}
            <div class="mb-3">
                <label for="product_id" class="form-label text-danger">Produit</label>
                <select name="product_id" id="product_id" class="form-select" required>
                    <option value="">-- Sélectionner un produit --</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $exit->product_id == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->brand }} - {{ $product->model }}
                            ({{ $product->store->name ?? 'Magasin inconnu' }})
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantity" class="form-label text-danger">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control"
                       value="{{ old('quantity', $exit->quantity) }}" required min="1">
            </div>

            {{-- Date --}}
            <div class="mb-3">
                <label for="exit_date" class="form-label text-danger">Date de sortie</label>
                <input type="date" name="exit_date" id="exit_date" class="form-control"
                       value="{{ old('exit_date', \Carbon\Carbon::parse($exit->exit_date)->format('Y-m-d')) }}" required>
            </div>

            {{-- Raison --}}
            <div class="mb-3">
                <label for="reason" class="form-label text-danger">Raison (facultative)</label>
                <input type="text" name="reason" id="reason" class="form-control"
                       value="{{ old('reason', $exit->reason) }}">
            </div>

            {{-- Magasin (admin uniquement) --}}
            @if (auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label for="store_id" class="form-label text-danger">Magasin</label>
                    <select name="store_id" id="store_id" class="form-select" required>
                        <option value="">-- Sélectionner un magasin --</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $exit->store_id == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            {{-- Boutons --}}
            <div class="text-end">
                <a href="{{ route('stock_exits.index') }}" class="btn btn-outline-secondary">Annuler</a>
                <button type="submit" class="btn btn-danger">💾 Mettre à jour</button>
            </div>
        </form>
    </div>
</x-app-layout>
