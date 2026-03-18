<x-app-layout>
    <div class="container mt-5">
        <div class="alert alert-warning">
            <h4 class="alert-heading text-danger">⚠️ Confirmation de suppression</h4>
            <p>Vous êtes sur le point de <strong>supprimer définitivement</strong> cette sortie de stock :</p>

            <ul class="mt-3">
                <li><strong>Produit :</strong> {{ $exit->product->name ?? 'Produit inconnu' }}</li>
                <li><strong>Quantité :</strong> {{ $exit->quantity }}</li>
                <li><strong>Date de sortie :</strong> {{ \Carbon\Carbon::parse($exit->exit_date)->format('d/m/Y') }}</li>
                <li><strong>Raison :</strong> {{ $exit->reason ?? 'Non spécifiée' }}</li>
                <li><strong>Magasin :</strong> {{ $exit->store->name ?? 'Magasin inconnu' }}</li>
            </ul>

            <p class="mt-3 text-danger">Cette action est irréversible.</p>
        </div>

        <div class="d-flex justify-content-between">
            <a href="{{ route('stock_exits.index') }}" class="btn btn-outline-secondary">Annuler</a>

            <form method="POST" action="{{ route('stock_exits.destroy', $exit->id) }}">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger">🗑️ Supprimer définitivement</button>
            </form>
        </div>
    </div>
</x-app-layout>
