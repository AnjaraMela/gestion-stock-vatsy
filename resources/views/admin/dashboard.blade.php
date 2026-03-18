<x-app-layout>
    <div class="container py-4">
        <h2 class="text-2xl font-bold mb-4">Tableau de bord </h2>

        <div class="grid grid-cols-1 md:grid-cols-4 lg-grid-cols-4 gap-4">

            <div class="bg p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Utilisateurs</h4>
                <p class="text-2xl">{{ $totalUsers }}</p>
            </div>

            <div class="bg p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Produits</h4>
                <p class="text-2xl">{{ $totalProducts }}</p>
            </div>

            <!--div class="bg p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Entrées stock</h4>
                <p class="text-2xl">{{ $stockIn }}</p>
            </div-->

            <div class="bg p-4 rounded shadow">
                <h4 class="text-lg font-semibold">Ventes</h4>
                <p class="text-2xl">{{ $totalSales }}</p>
            </div>

            <div class="bg p-4 rounded shadow">
                <h4 class="text-lg font-semibold"> Stock</h4>
                <p class="text-2xl">{{ $stockTotal }}</p>
            </div>
    
        </div>
        <div class="mt-10 bg-white p-6 rounded shadow">
                <h4 class="text-lg font-semibold mb-4">Ventes par mois</h4>
                <canvas id="salesChart" width="400" height="200"></canvas>
        </div>
        
    </div>

    {{-- Script Chart.js --}}
    
    <script>
        import Chart from 'chart.js/auto';
        document.addEventlistener('DOMContentLoaded', function());

        const ctx = document.getElementById('salesChart').getContext('2d');
         new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Jan','fev','Mar','Apr'],
                datasets: [{
                    label: 'Nombre de ventes par mois',
                    data: @json($data),
                    backgroundColor: 'rgba(100, 100, 100, 0.6)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        title: {
                            display: true,
                            text: 'Nombre de ventes'
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
