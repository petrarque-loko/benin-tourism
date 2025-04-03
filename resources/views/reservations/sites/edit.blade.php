@extends('layouts.app')

@section('content')
    <style>
        [x-cloak] { display: none !important; }
        
        .fade-in-up {
            animation: fadeInUp 0.5s ease-out forwards;
        }
        
        .slide-in {
            animation: slideIn 0.3s ease-out forwards;
        }
        
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(-20px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                transform: scale(1);
            }
            50% {
                transform: scale(1.05);
            }
            100% {
                transform: scale(1);
            }
        }
        
        .custom-calendar {
            background: linear-gradient(135deg, #f6f9fc 0%, #eef1f5 100%);
        }
        
        .date-selected {
            background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
            color: white;
            transform: scale(1.05);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        .guide-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-50 min-h-screen">
    <div x-data="reservationForm()" x-cloak class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-indigo-800 mb-6 flex items-center animate__animated animate__fadeIn">
            <i class="fas fa-edit mr-3"></i>Modifier la Réservation
        </h1>

        <!-- Notifications -->
        <div x-show="hasErrors" class="mb-6 fade-in-up">
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-md shadow-md" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2 text-red-500"></i>
                    <p class="font-bold">Veuillez corriger les erreurs suivantes :</p>
                </div>
                <ul class="ml-6 mt-2 list-disc">
                    <template x-for="error in errors" :key="error">
                        <li x-text="error" class="slide-in"></li>
                    </template>
                </ul>
            </div>
        </div>

        <div x-show="showSuccess" class="mb-6 animate__animated animate__fadeIn">
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-md shadow-md" role="alert">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2 text-green-500"></i>
                    <p class="font-bold">Réservation mise à jour avec succès!</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
            <!-- Détails de la réservation actuelle -->
            <div class="fade-in-up" style="animation-delay: 0.1s">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:border-indigo-200 transition-all duration-300">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-4 px-6">
                        <h2 class="text-xl font-semibold flex items-center">
                            <i class="fas fa-info-circle mr-2"></i>Détails de la Réservation Actuelle
                        </h2>
                    </div>
                    <div class="p-6">
                        <div class="flex items-center mb-4">
                            <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center mr-4">
                                <i class="fas fa-landmark text-indigo-600 text-xl"></i>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800" x-text="currentReservation.site || 'Site supprimé'"></h3>
                        </div>
                        
                        <div class="space-y-3 mt-6">
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-calendar-alt text-blue-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Dates actuelles</p>
                                    <p class="text-gray-800 font-medium" x-text="`Du ${formatDate(currentReservation.date_debut)} au ${formatDate(currentReservation.date_fin)}`"></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-user-tie text-green-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Guide actuel</p>
                                    <p class="text-gray-800 font-medium" x-text="currentReservation.guide"></p>
                                </div>
                            </div>
                            
                            <div class="flex items-center">
                                <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center mr-3">
                                    <i class="fas fa-bookmark text-purple-600"></i>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Statut</p>
                                    <span 
                                        x-text="currentReservation.statut"
                                        :class="{
                                            'bg-green-100 text-green-800': currentReservation.statut === 'Confirmée',
                                            'bg-yellow-100 text-yellow-800': currentReservation.statut === 'En attente',
                                            'bg-red-100 text-red-800': currentReservation.statut === 'Annulée',
                                            'bg-blue-100 text-blue-800': currentReservation.statut === 'Terminée'
                                        }"
                                        class="px-3 py-1 rounded-full text-sm font-medium"
                                    ></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-6 pt-6 border-t border-gray-100">
                            <div class="flex items-center text-gray-500">
                                <i class="fas fa-clock mr-2"></i>
                                <p class="text-sm">Dernière modification: <span x-text="formatDateTime(currentReservation.updated_at)"></span></p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de modification -->
            <div class="fade-in-up" style="animation-delay: 0.2s">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100 hover:border-indigo-200 transition-all duration-300">
                    <div class="bg-gradient-to-r from-indigo-600 to-blue-500 text-white py-4 px-6">
                        <h2 class="text-xl font-semibold flex items-center">
                            <i class="fas fa-edit mr-2"></i>Modifier la Réservation
                        </h2>
                    </div>
                    <div class="p-6">
                        <form @submit.prevent="submitForm" id="reservationForm">
                            <div class="mb-6">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i class="fas fa-calendar-alt mr-2 text-indigo-500"></i>
                                    Nouvelles Dates de Réservation
                                </label>
                                
                                <div class="bg-blue-50 p-4 rounded-lg mb-4 text-sm text-blue-700 flex items-center">
                                    <i class="fas fa-info-circle mr-2"></i>
                                    <span>Durée de la réservation: <strong x-text="calculateDuration() + ' jours'"></strong></span>
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-gray-600 text-sm mb-1">Date de début</label>
                                        <div class="relative">
                                            <input 
                                                type="date" 
                                                x-model="formData.date_debut" 
                                                @change="validateDates()"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
                                                :class="{'border-red-300': errors.includes('Les dates sont invalides')}"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <div>
                                        <label class="block text-gray-600 text-sm mb-1">Date de fin</label>
                                        <div class="relative">
                                            <input 
                                                type="date" 
                                                x-model="formData.date_fin" 
                                                @change="validateDates()"
                                                class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-all duration-200"
                                                :class="{'border-red-300': errors.includes('Les dates sont invalides')}"
                                            >
                                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                <i class="fas fa-calendar text-gray-400"></i>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                
                                <div x-show="dateError" class="mt-2 text-sm text-red-600 slide-in">
                                    <i class="fas fa-exclamation-circle mr-1"></i>
                                    <span x-text="dateErrorMessage"></span>
                                </div>
                            </div>

                            <div class="mb-6">
                                <label class="block text-gray-700 font-medium mb-2 flex items-center">
                                    <i class="fas fa-user-tie mr-2 text-indigo-500"></i>
                                    Guides Disponibles
                                </label>
                                
                                <div x-show="guides.length === 0" class="bg-yellow-50 p-4 rounded-lg mb-4 text-yellow-700 flex items-center">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>
                                    <span>Aucun guide disponible pour ces dates.</span>
                                </div>
                                
                                <div class="grid grid-cols-1 gap-4">
                                    <template x-for="guide in guides" :key="guide.id">
                                        <div 
                                            class="guide-card border rounded-lg p-4 transition-all duration-300 cursor-pointer"
                                            :class="formData.guide_id === guide.id ? 'bg-indigo-50 border-indigo-300' : 'bg-white border-gray-200 hover:bg-gray-50'"
                                            @click="selectGuide(guide.id)"
                                        >
                                            <div class="flex items-center">
                                                <div class="mr-3">
                                                    <div class="w-12 h-12 rounded-full bg-indigo-100 flex items-center justify-center">
                                                        <i class="fas fa-user text-indigo-600"></i>
                                                    </div>
                                                </div>
                                                <div class="flex-1">
                                                    <h4 class="font-medium text-gray-800" x-text="`${guide.nom} ${guide.prenom}`"></h4>
                                                    <div class="flex items-center mt-1">
                                                        <div class="flex items-center">
                                                            <template x-for="i in 5" :key="i">
                                                                <i 
                                                                class="fas fa-star text-sm"
                                                    :class="i <= guide.note_moyenne ? 'text-yellow-500' : 'text-gray-300'"
                                                ></i>
                                            </template>
                                            <span class="text-sm text-gray-500 ml-1" x-text="`(${guide.note_moyenne || 'Non noté'})`"></span>
                                        </div>
                                    </div>
                                    
                                    <div x-show="formData.guide_id === guide.id" class="mt-2 flex items-center text-indigo-600 slide-in">
                                        <i class="fas fa-check-circle mr-1"></i>
                                        <span class="text-sm font-medium">Guide sélectionné</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                </div>
            </div>

                <div class="mt-8 flex items-center justify-between">
                    <button type="button" @click="goBack" class="px-4 py-2 text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 transition-all duration-200 flex items-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour
                    </button>
                    
                    <button 
                        type="submit" 
                        :disabled="guides.length === 0 || formData.guide_id === null"
                        class="px-6 py-2 bg-gradient-to-r from-indigo-600 to-blue-500 text-white rounded-lg hover:shadow-lg transition-all duration-200 flex items-center"
                        :class="{'opacity-50 cursor-not-allowed': guides.length === 0 || formData.guide_id === null, 'hover:from-indigo-700 hover:to-blue-600': guides.length > 0 && formData.guide_id !== null}"
                    >
                        <i class="fas fa-save mr-2"></i>
                        Mettre à jour la réservation
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>

<script>
function reservationForm() {
    return {
        currentReservation: {
            id: {{ $reservation->id }},
            site: '{{ $reservation->reservable ? $reservation->reservable->nom : "Site supprimé" }}',
            date_debut: '{{ \Carbon\Carbon::parse($reservation->date_debut)->format("Y-m-d") }}',
            date_fin: '{{ \Carbon\Carbon::parse($reservation->date_fin)->format("Y-m-d") }}',
            guide: '{{ $reservation->guide->nom }} {{ $reservation->guide->prenom }}',
            guide_id: {{ $reservation->guide_id }},
            statut: '{{ $reservation->statut }}',
            updated_at: '{{ $reservation->updated_at }}'
        },
        formData: {
            date_debut: '{{ \Carbon\Carbon::parse($reservation->date_debut)->format("Y-m-d") }}',
            date_fin: '{{ \Carbon\Carbon::parse($reservation->date_fin)->format("Y-m-d") }}',
            guide_id: {{ $reservation->guide_id }}
        },
        guides: JSON.parse('{!! json_encode($guides->map(function($guide) { 
            return [
                "id" => $guide->id,
                "nom" => $guide->nom,
                "prenom" => $guide->prenom,
                "note_moyenne" => $guide->commentaires->avg("note") ?? 0
            ]; 
        })) !!}'),
        errors: [],
        hasErrors: false,
        showSuccess: false,
        dateError: false,
        dateErrorMessage: '',
        
        init() {
            // Si on vient de mettre à jour avec succès la réservation
            if (window.location.search.includes('success=true')) {
                this.showSuccess = true;
                setTimeout(() => {
                    this.showSuccess = false;
                }, 5000);
            }
            
            this.validateDates();
        },
        
        formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('fr-FR');
        },
        
        formatDateTime(dateTimeString) {
            return moment(dateTimeString).locale('fr').format('DD/MM/YYYY à HH:mm');
        },
        
        calculateDuration() {
            const start = new Date(this.formData.date_debut);
            const end = new Date(this.formData.date_fin);
            const diffTime = Math.abs(end - start);
            const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1;
            return diffDays;
        },
        
        validateDates() {
            this.dateError = false;
            this.dateErrorMessage = '';
            this.errors = this.errors.filter(e => !e.includes('Les dates'));
            
            const start = new Date(this.formData.date_debut);
            const end = new Date(this.formData.date_fin);
            const today = new Date();
            today.setHours(0, 0, 0, 0);
            
            if (start < today) {
                this.dateError = true;
                this.dateErrorMessage = 'La date de début ne peut pas être dans le passé';
                this.errors.push('Les dates sont invalides');
            } else if (end < start) {
                this.dateError = true;
                this.dateErrorMessage = 'La date de fin doit être après la date de début';
                this.errors.push('Les dates sont invalides');
            }
            
            this.hasErrors = this.errors.length > 0;
            
            // Si les dates ont changé, nous devrions charger les guides disponibles
            if (!this.dateError) {
                this.loadAvailableGuides();
            }
        },
        
        loadAvailableGuides() {
            // Dans une application réelle, nous ferions un appel AJAX ici
            // Mais pour l'exemple, nous simulerons le chargement des guides
            const loading = document.createElement('div');
            loading.className = 'text-center p-4';
            loading.innerHTML = '<i class="fas fa-circle-notch fa-spin text-indigo-500 text-2xl"></i>';
            
            // Simulation d'un appel API
            fetch(`/api/guides/available?site_id={{ $reservation->reservable_id }}&date_debut=${this.formData.date_debut}&date_fin=${this.formData.date_fin}`)
                .then(response => response.json())
                .then(data => {
                    this.guides = data;
                    
                    // Si le guide actuel n'est pas disponible, on reset
                    if (this.guides.length > 0 && !this.guides.some(g => g.id === this.formData.guide_id)) {
                        this.formData.guide_id = null;
                    }
                })
                .catch(error => {
                    console.error('Erreur lors du chargement des guides:', error);
                    // En cas d'erreur, on simule simplement quelques guides disponibles
                    // Ceci est uniquement pour la démonstration
                });
        },
        
        selectGuide(guideId) {
            this.formData.guide_id = guideId;
        },
        
        goBack() {
            window.history.back();
        },
        
        submitForm() {
            this.validateDates();
            
            if (this.hasErrors) {
                document.querySelector('.alert-danger').scrollIntoView({ behavior: 'smooth' });
                return;
            }

            // Récupération du formulaire et du token CSRF
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').getAttribute('content'));
            formData.append('_method', 'PUT');
            formData.append('date_debut', this.formData.date_debut);
            formData.append('date_fin', this.formData.date_fin);
            formData.append('guide_id', this.formData.guide_id);

            fetch(`{{ route("touriste.reservations.sites.update", $reservation->id) }}`, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(data => {
                        throw new Error(data.message || 'Erreur lors de la mise à jour');
                    });
                }
                return response.json();
            })
            .then(() => {
                window.location.href = '{{ route("touriste.reservations.sites.index") }}?success=true';
            })
            .catch(error => {
                this.errors.push(error.message);
                this.hasErrors = true;
            });
        }

    };
}
</script>

@endsection