<x-app-layout>
    <div class="container mt-4">
        <h2 class="mb-4 text-success">Modifier l'entrée de stock</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('stock_entries.update', $stockEntry->id) }}">
            @csrf
            @method('PUT')

            {{-- Produit (non modifiable) --}}
            <div class="mb-3">
                <label class="form-label">Produit</label>
                <input type="text" class="form-control" value="{{ $stockEntry->product->name }} ({{ $stockEntry->product->brand }} - {{ $stockEntry->product->model }})" disabled>
            </div>

            {{-- Quantité --}}
            <div class="mb-3">
                <label for="quantity" class="form-label">Quantité</label>
                <input type="number" name="quantity" id="quantity" class="form-control" min="1" required value="{{ old('quantity', $stockEntry->quantity) }}">
            </div>

            {{-- Date d'entrée --}}
            <div class="mb-3">
                <label for="entry_date" class="form-label">Date d'entrée</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control" required value="{{ old('entry_date', $stockEntry->entry_date->format('Y-m-d')) }}">
            </div>

            {{-- Magasin (affiché uniquement pour l'admin) --}}
            @if(auth()->user()->role === 'admin')
                <div class="mb-3">
                    <label class="form-label">Magasin</label>
                    <input type="text" class="form-control" value="{{ $stockEntry->store->name }}" disabled>
                </div>
            @endif

            <div class="text-end">
                <a href="{{ route('stock_entries.index') }}" class="btn btn-secondary">↩️ Annuler</a>
                <button type="submit" class="btn btn-primary">💾 Mettre à jour</button>
            </div>
        </form>
    </div>
</x-app-layout>
