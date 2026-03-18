<x-app-layout>
<div class="max-w-2xl mx-auto px-4">
    <h1 class="text-2xl font-bold mb-4">Modifier utilisateur</h1>

    <form action="{{ route('admin.users.update', $user->id) }}" method="POST" class="bg-white p-4 rounded shadow">
        @csrf
        @method('PUT')

        <input type="text" name="id" style="display:none;" value="{{ $user->id }}">

        <div class="mb-4">
            <label class="block mb-1">Nom</label>
            <input type="text" name="name" class="w-full border p-2 rounded" value="{{ old('name', $user->name) }}" required>
        </div>

        <div class="mb-4">
            <label class="block mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="mb-4">
    <label class="block mb-1">Rôle</label>
    <!-----select name="role" id="role-select" class="w-full border p-2 rounded" required>
        <option value="{{ $user->role === 'user' ? 'selected' : '' }}">Utilisateur simple</option>
        <option value="{{$user->role === 'admin' ? 'selected' : '' }}">Administrateur</option>
    </select-->

    <select name="role" id="role-select" class="w-full border p-2 rounded" required>
        <option value="user" {{ $user->role === 'user' ? 'selected' : '' }}>Utilisateur simple</option>
        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Administrateur</option>
    </select>
</div>

<div class="mb-4" id="store-select-block">
    <label class="block mb-1">Magasin</label>
    <select name="store_id" class="w-full border p-2 rounded">
        <option value="">-- Sélectionner --</option>
        @foreach($stores as $store)
            <option value="{{ $store->id }}" {{ $user->store_id == $store->id ? 'selected' : '' }}>
                {{ $store->name }}
            </option>
        @endforeach
    </select>
</div>

@error('store_id')
    <div class="text-danger text-sm mt-1">{{ $message }}</div>
@enderror

<button type="submit" class="bg-green-500 text-white px-4 py-2 rounded">Mettre à jour</button>
    </form>
</div>

<script>
   
    const roleSelect = document.getElementById('role-select');
    const storeBlock = document.getElementById('store-select-block');

    function toggleStoreField() {
        storeBlock.style.display = roleSelect.value === 'user' ? 'block' : 'none';
    }

    roleSelect.addEventListener('change', toggleStoreField);
    window.addEventListener('DOMContentLoaded', toggleStoreField);
</script>

       

</x-app-layout>
