<x-app-layout>
<div class="container">
    <h1>Transférer un stock</h1>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <form action="{{ route('transfers.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label>Produit</label>
            <select name="product_id" class="form-select">
                @foreach ($products as $product)
                    <option value="{{ $product->id }}">{{ $product->brand }} {{ $product->model }} ({{ $product->store->name }})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>De</label>
            <select name="from_store_id" class="form-select">
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Vers</label>
            <select name="to_store_id" class="form-select">
                @foreach ($stores as $store)
                    <option value="{{ $store->id }}">{{ $store->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label>Quantité</label>
            <input type="number" name="quantity" class="form-control" required min="1">
        </div>
        <button class="btn btn-primary">Transférer</button>
    </form>
</div>
</x-app-layout>
