@extends('layouts.admin')

@section('title', 'Statistiques des Hébergements')

@section('content')
<div class="py-6 px-4" x-data="statisticsData()">
    <div class="max-w-7xl mx-auto">
        <!-- En-tête -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between">
            <h1 class="text-2xl font-bold text-gray-900">Statistiques des Hébergements</h1>
            <div class="mt-4 md:mt-0">
                <button 
                    @click="printStatistics()" 
                    class="inline-flex items-center px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700 print-button"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Imprimer
                </button>
            </div>
        </div>

        <!-- Cartes de statistiques générales -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-blue-500">
                <h2 class="text-lg font-semibold text-gray-700">Total des Hébergements</h2>
                <p class="text-3xl font-bold text-blue-600 mt-2">{{ $totalHebergements }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500">
                <h2 class="text-lg font-semibold text-gray-700">Disponibles</h2>
                <p class="text-3xl font-bold text-green-600 mt-2">{{ \App\Models\Hebergement::where('disponibilite', true)->count() }}</p>
            </div>
            <div class="bg-white rounded-lg shadow p-6 border-l-4 border-red-500">
                <h2 class="text-lg font-semibold text-gray-700">Indisponibles</h2>
                <p class="text-3xl font-bold text-red-600 mt-2">{{ \App\Models\Hebergement::where('disponibilite', false)->count() }}</p>
            </div>
        </div>

        <!-- Statistiques par type d'hébergement -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="p-6 border-b">
                <h2 class="text-xl font-semibold text-gray-800">Hébergements par Type</h2>
            </div>
            <div class="p-6">
                <div x-show="!loadingChart" class="h-64">
                    <canvas id="typeHebergementChart"></canvas>
                </div>
                <div x-show="loadingChart" class="flex justify-center items-center h-64">
                    <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
                <div class="mt-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type d'hébergement</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Pourcentage</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($hebergementsParType as $type)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $type->typeHebergement->nom }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $type->total }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">
                                    {{ round(($type->total / $totalHebergements) * 100, 1) }}%
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Distribution par ville -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Top 10 des Villes</h2>
                </div>
                <div class="p-6">
                    <div x-show="!loadingChart" class="h-64">
                        <canvas id="villeChart"></canvas>
                    </div>
                    <div x-show="loadingChart" class="flex justify-center items-center h-64">
                        <svg class="animate-spin h-8 w-8 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>
                    <div class="mt-6 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
                                    <th class="px-6 py-3 bg-gray-50 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Nombre</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($hebergementsParVille as $ville)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $ville->ville }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-right">{{ $ville->total }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- Tendance de disponibilité -->
            <div class="bg-white rounded-lg shadow">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-semibold text-gray-800">Tendance de disponibilité</h2>
                </div>
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <button @click="prevMonth()" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                            </svg>
                        </button>
                        <span x-text="currentPeriod" class="text-lg font-medium text-gray-700"></span>
                        <button @click="nextMonth()" class="text-gray-600 hover:text-gray-900">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </button>
                    </div>
                    <div class="h-64">
                        <canvas id="disponibiliteChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    function statisticsData() {
        return {
            loadingChart: true,
            currentPeriod: 'Mars 2025',
            months: ['Janvier', 'Février', 'Mars', 'Avril', 'Mai', 'Juin', 'Juillet', 'Août', 'Septembre', 'Octobre', 'Novembre', 'Décembre'],
            currentMonthIndex: 2, // Mars par défaut

            init() {
                setTimeout(() => {
                    this.loadingChart = false;
                    this.initCharts();
                }, 500);
                this.updateCurrentPeriod();
            },

            initCharts() {
                // Graphique des types d'hébergement
                const typeCtx = document.getElementById('typeHebergementChart').getContext('2d');
                const typeData = {
                    labels: @json($hebergementsParType->pluck('typeHebergement.nom')),
                    datasets: [{
                        label: 'Nombre d\'hébergements',
                        data: @json($hebergementsParType->pluck('total')),
                        backgroundColor: [
                            'rgba(54, 162, 235, 0.6)',
                            'rgba(255, 99, 132, 0.6)',
                            'rgba(255, 206, 86, 0.6)',
                            'rgba(75, 192, 192, 0.6)',
                            'rgba(153, 102, 255, 0.6)',
                            'rgba(255, 159, 64, 0.6)'
                        ],
                        borderColor: [
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 99, 132, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                };
                new Chart(typeCtx, {
                    type: 'pie',
                    data: typeData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: { position: 'right' }
                        }
                    }
                });

                // Graphique des villes
                const villeCtx = document.getElementById('villeChart').getContext('2d');
                const villeData = {
                    labels: @json($hebergementsParVille->pluck('ville')),
                    datasets: [{
                        label: 'Nombre d\'hébergements',
                        data: @json($hebergementsParVille->pluck('total')),
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                };
                new Chart(villeCtx, {
                    type: 'bar',
                    data: villeData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: { beginAtZero: true }
                        }
                    }
                });

                // Graphique de disponibilité
                this.updateDisponibiliteChart();
            },

            async updateDisponibiliteChart() {
                const year = new Date().getFullYear(); // Année actuelle (ajustez si nécessaire)
                const month = this.currentMonthIndex + 1; // Mois de 1 à 12 (ajustez selon votre logique)

                try {
                    // Requête AJAX vers la route Laravel
                    const response = await fetch(`/proprietaire/hebergements-disponibilite/${year}/${month}`);
                    const data = await response.json();

                    // Préparer le contexte du graphique
                    const disponibiliteCtx = document.getElementById('disponibiliteChart').getContext('2d');
                    if (window.disponibiliteChart) {
                        window.disponibiliteChart.destroy(); // Détruire le graphique existant
                    }

                    // Structurer les données pour Chart.js
                    const disponibiliteData = {
                        labels: data.days,
                        datasets: [
                            {
                                label: 'Disponibles',
                                data: data.disponibles,
                                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                                borderColor: 'rgba(75, 192, 192, 1)',
                                borderWidth: 1,
                                tension: 0.4
                            },
                            {
                                label: 'Indisponibles',
                                data: data.indisponibles,
                                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                                borderColor: 'rgba(255, 99, 132, 1)',
                                borderWidth: 1,
                                tension: 0.4
                            }
                        ]
                    };

                    // Créer le nouveau graphique
                    window.disponibiliteChart = new Chart(disponibiliteCtx, {
                        type: 'line',
                        data: disponibiliteData,
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: { y: { beginAtZero: true } },
                            plugins: {
                                title: {
                                    display: true,
                                    text: `Disponibilité pour ${this.currentPeriod}`
                                }
                            }
                        }
                    });
                } catch (error) {
                    console.error('Erreur lors de la récupération des données:', error);
                }
            },

            prevMonth() {
                this.currentMonthIndex = (this.currentMonthIndex - 1 + 12) % 12;
                this.updateCurrentPeriod();
                this.updateDisponibiliteChart();
            },

            nextMonth() {
                this.currentMonthIndex = (this.currentMonthIndex + 1) % 12;
                this.updateCurrentPeriod();
                this.updateDisponibiliteChart();
            },

            updateCurrentPeriod() {
                const currentDate = new Date();
                const year = currentDate.getFullYear();
                this.currentPeriod = `${this.months[this.currentMonthIndex]} ${year}`;
            },

            printStatistics() {
                const originalStyle = document.body.style.cssText;
                const style = document.createElement('style');
                style.textContent = `
                    @media print {
                        body { padding: 20px; font-family: Arial, sans-serif; }
                        .print-button { display: none !important; }
                        .print-only { display: block !important; }
                        canvas { max-height: 300px !important; }
                        table { width: 100%; border-collapse: collapse; }
                        table th, table td { border: 1px solid #ddd; padding: 8px; }
                        h1 { font-size: 24px; margin-bottom: 20px; }
                        h2 { font-size: 18px; margin-bottom: 15px; }
                    }
                `;
                document.head.appendChild(style);

                const printDate = document.createElement('div');
                printDate.classList.add('print-only');
                printDate.style.display = 'none';
                printDate.innerHTML = `<p class="text-sm text-gray-500 mb-4">Imprimé le ${new Date().toLocaleDateString()} à ${new Date().toLocaleTimeString()}</p>`;
                document.querySelector('.max-w-7xl').prepend(printDate);

                window.print();

                document.head.removeChild(style);
                document.querySelector('.max-w-7xl').removeChild(printDate);
                document.body.style.cssText = originalStyle;
            }
        }
    }
</script>
@endpush
