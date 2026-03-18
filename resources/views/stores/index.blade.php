<x-app-layout>
<div class="container"> 
    <h1>Liste des magasins</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <a href="{{ route('stores.create') }}" class="btn btn-success mb-3">➕ Ajouter un magasin</a>

    <table class="table table-bordered">
        <thead class="table-danger text-white">
            <tr>
                <th>Nom</th>
                <th>Emplacement</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($stores as $store)
                <tr>
                    <td>{{ $store->name }}</td>
                    <td>{{ $store->location }}</td>
                    <td>
                        <a href="{{ route('stores.edit', $store->id) }}" class="btn btn-sm btn-outline-success">✏️ Modifier</a>

                        <form action="{{ route('stores.destroy', $store->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Supprimer ce magasin ?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-outline-danger">🗑️ Supprimerr</button>
                        </form>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
</x-app-layout>
