<x-app-layout>
<div class="max-w-7xl mx-auto px-4 mt-4">
    <h1 class="text-2xl font-bold mb-4">Liste des utilisateurs</h1>

    @if(session('success'))
        <div class="bg-green-200 p-2 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif
    <div class="mb-3">
        <a href="{{ route('admin.users.create') }}" class="btn btn-success">
            ➕Ajouter un utilisateur
        </a>
    </div>   
     
    <div class="table-responsive">
    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr class="table-danger text-white">
                <th class="p-2">Nom</th>
                <th class="p-2">Email</th>
                <th class="p-2">Rôle</th>
                <th class="p-2">Magasin</th>
                <th class="p-2">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)

            
            <tr class="border-b">
                <td class="p-2">{{ $user->name }}</td>
                <td class="p-2">{{ $user->email }}</td>
                <td class="p-2">{{ $user->role }}</td>
                <td class="p-2"> {{ $user->store->name ?? 'Aucun magasin' }}</td>
                <td class="p-2">
                    <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-sm btn-outline-success">
                    ✏️ Modifier</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Supprimer cet utilisateur ?')" class="btn btn-sm btn-outline-danger" type="submit">
                         🗑️ Supprimer</button>
                    </form>
                </td>
            </tr>
            @endforeach
            
        </tbody>
    </table>
</div>
</x-app-layout>
