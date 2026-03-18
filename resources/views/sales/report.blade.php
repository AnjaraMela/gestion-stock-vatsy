<x-app-layout>
    <div class="container mt-4">
        <h1 class="mb-4">📊 Rapport des ventes</h1>

        {{-- Formulaire de filtre --}}
        <form method="GET" class="row g-3 mb-4">
            @if(auth()->user()->role === 'admin')
                <div class="col-md-3">
                    <label class="form-label">Magasin</label>
                    <select name="store_id" class="form-select">
                        <option value="">Tous les magasins</option>
                        @foreach ($stores as $store)
                            <option value="{{ $store->id }}" {{ $selected_store == $store->id ? 'selected' : '' }}>
                                {{ $store->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            @endif

            <div class="col-md-3">
                <label class="form-label">Produit</label>
                <select name="product_id" class="form-select">
                    <option value="">Tous les produits</option>
                    @foreach ($products as $product)
                        <option value="{{ $product->id }}" {{ $selected_product == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - {{ $product->brand }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-3">
                <label class="form-label">Du</label>
                <input type="date" name="start_date" class="form-control" value="{{ $start_date }}">
            </div>

            <div class="col-md-3">
                <label class="form-label">Au</label>
                <input type="date" name="end_date" class="form-control" value="{{ $end_date }}">
            </div>

            <div class="col-md-12 text-end">
                <button type="submit" class="btn btn-outline-success">🔍 Filtrer</button>
                <a href="{{ route('sales.report') }}" class="btn btn-outline-secondary">↺ Réinitialiser</a>
            </div>
        </form>

        {{-- Résumé --}}
        <div class="mb-3">
            <strong>Total ventes :</strong> {{ $totalSales }} <br>
            <strong>Revenu total :</strong> {{ number_format($totalRevenue, 0, ',', ' ') }} Ar
        </div>

        {{-- Tableau des rapports --}}
        <div class="table-responsive">
            <table class="table table-bordered table-hover">
                <thead class="table-danger">
                    <tr>
                        <th>Date</th>
                        <th>Produit</th>
                        <th>Quantité</th>
                        <th>Prix unitaire</th>
                        <th>Revenu</th>
                        @if(auth()->user()->role === 'admin')
                            <th>Magasin</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @forelse ($report as $entry)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($entry->sale_date)->format('d/m/Y') }}</td>
                            <td>{{ $entry->product->name }} - {{ $entry->product->brand }}</td>
                            <td>{{ $entry->total_quantity }}</td>
                            <td>{{ number_format($entry->product->sale_price, 0, ',', ' ') }} Ar</td>
                            <td>{{ number_format($entry->total_revenue, 0, ',', ' ') }} Ar</td>
                            @if(auth()->user()->role === 'admin')
                                <td>{{ $entry->store->name }}</td>
                            @endif
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ auth()->user()->role === 'admin' ? 6 : 5 }}" class="text-center text-muted">Aucune vente trouvée pour ces filtres.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-app-layout>
