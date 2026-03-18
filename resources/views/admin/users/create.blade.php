<x-app-layout>
<div class="max-w-2xl mx-auto px-4">
    <h1 class="mb-4 text-success">➕Créer un utilisateur</h1>

    <form action="{{ route('admin.users.store') }}" method="POST" class="bg-white p-4 rounded shadow">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nom</label>
            <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name') }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email') }}" required>
        </div>

        <div class="mb-4">
    <label class="block mb-1">Rôle</label>
    <select name="role" id="role-select" class="w-full border p-2 rounded" required>
        <option value="user" {{ old('role') === 'user' ? 'selected' : '' }}>Utilisateur simple</option>
        <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Administrateur</option>
    </select>
</div>

<div class="mb-4" id="store-select-block">
    <label class="block mb-1">Magasin</label>
    <select name="store_id" class="w-full border p-2 rounded">
        <option value="">-- Sélectionner --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ old('store_id') == $store->id ? 'selected' : '' }}>
                {{ $store->name }}
            </option>
        @endforeach
    </select>
</div>

@error('store_id')
    <div class="text-danger text-sm mt-1">{{ $message }}</div>
@enderror

<script>
    const roleSelect = document.getElementById('role-select');
    const storeBlock = document.getElementById('store-select-block');

    function toggleStoreField() {
        storeBlock.style.display = roleSelect.value === 'user' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleStoreField);
    window.addEventListener('DOMContentLoaded', toggleStoreField);
</script>



        <div class="mb-4">
            <label class="block mb-1">Mot de passe</label>
            <input type="password" name="password" class="w-full border p-2 rounded" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Confirmation mot de passe</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded" required>
        </div>

        <button type="submit" class="btn btn-success">✅Créer</button>
        <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">↩️ Annuler</a>
    </form>
</div>
</x-app-layout>
