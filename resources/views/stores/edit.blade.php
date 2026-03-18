<x-app-layout>
<div class="container">
    <h1>Modifier le magasin</h1>

    <form action="{{ route('stores.update', $store->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="name" class="form-label">Nom</label>
            <input type="text" name="name" value="{{ $store->name }}" class="form-control" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Localisation</label>
            <input type="text" name="location" value="{{ $store->location }}" class="form-control">
            @error('location') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-primary">Mettre à jour</button>
        <a href="{{ route('stores.index') }}" class="btn btn-secondary">Annuler</a>
    </form>
</div>
</x-app-layout>
