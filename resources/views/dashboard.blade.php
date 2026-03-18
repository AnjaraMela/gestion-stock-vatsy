<x-app-layout>
    <div class="container py-4">
        <h2 class="text-2xl font-bold mb-6">Tableau de bord</h2>

        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div class="bg-white p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Produits</h4>
                <p class="text-2xl">{{ $productsCount }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Entrées stock</h4>
                <p class="text-2xl">{{ $stockEntriesCount }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Sorties stock</h4>
                <p class="text-2xl">{{ $stockExitsCount }}</p>
            </div>

            <div class="bg-white p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Ventes</h4>
                <p class="text-2xl">{{ $salesCount }}</p>
            </div>
            <div class="mt-10 bg-white p-6 rounded shadow">
                <h4 class="text-lg font-semibold mb-4">Ventes sur les 7 derniers jours</h4>
                <canvas id="salesChart" height="100"></canvas>
            </div>
        </div>
    </div>
    <!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    const ctx = document.getElementById('salesChart').getContext('2d');
    const salesChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: @json($labels),
            datasets: [{
                label: 'Ventes',
                data: @json($data),
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.4,
                fill: true,
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
</script>
</x-app-layout>
