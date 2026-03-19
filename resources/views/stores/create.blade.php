<x-app-layout>
<div class="max-w-2xl mx-auto px-4 py-5">
    <h2 class="mb-4 text-success">➕ Ajouter un magasin</h2>

    <form action="{{ route('stores.store') }}" method="POST" class="bg-white p-4 rounded shadow">
        @csrf

        <div class="mb-3">
            <label for="name" class="form-label">Nom du magasin</label>
            <input type="text" name="name" class="form-control" required>
            @error('name') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <div class="mb-3">
            <label for="location" class="form-label">Localisation</label>
            <input type="text" name="location" class="form-control">
            @error('location') <div class="text-danger">{{ $message }}</div> @enderror
        </div>

        <button type="submit" class="btn btn-success">✅ Enregistrer</button>
        <a href="{{ route('stores.index') }}" class="btn btn-secondary">↩️ Annuler</a>
    </form>
</div>
</x-app-layout>
