@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="{
    selectedGuide: null,
    showGuideDetails: false,
    isLoading: false,
    submitReservation() {
        this.isLoading = true;
        // Simulation d'un délai pour montrer le chargement
        setTimeout(() => {
            document.getElementById('reservation-form').submit();
        }, 800);
    }
}">
    <!-- Titre avec animation -->
    <div class="flex items-center mb-8 overflow-hidden">
        <h1 
            class="text-3xl font-bold text-indigo-800 transform transition-all duration-700 hover:scale-105"
            x-intersect="$el.classList.add('translate-x-0', 'opacity-100')"
            x-intersect:leave="$el.classList.remove('translate-x-0', 'opacity-100')"
            :class="{'translate-x-0 opacity-100': true, '-translate-x-full opacity-0': false}"
        >
            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
            Réserver {{ $site->nom }}
        </h1>
    </div>

    <!-- Affichage des erreurs avec animation -->
    @if($errors->any())
        <div 
            class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded shadow-md"
            x-data="{ show: true }"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform -translate-y-4"
            x-transition:enter-end="opacity-100 transform translate-y-0"
            x-transition:leave="transition ease-in duration-300"
            x-transition:leave-start="opacity-100 transform translate-y-0"
            x-transition:leave-end="opacity-0 transform -translate-y-4"
        >
            <div class="flex justify-between items-center">
                <div>
                    <h3 class="font-bold flex items-center">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        Erreurs
                    </h3>
                    <ul class="mt-2 list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                <button @click="show = false" class="text-red-500 hover:text-red-700">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Détails du site -->
        <div 
            class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl transform hover:-translate-y-1"
            x-intersect="$el.classList.add('translate-x-0', 'opacity-100')"
            x-intersect:leave="$el.classList.remove('translate-x-0', 'opacity-100')"
            :class="{'translate-x-0 opacity-100': true, '-translate-x-full opacity-0': false}"
            >
            <div class="bg-gradient-to-r from-blue-600 to-indigo-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Détails du Site
                </h2>
            </div>
            
            <div class="p-6">
                <!-- Images du site  -->
                <div class="relative rounded-lg overflow-hidden mb-4 aspect-[16/9] bg-gray-300">
                @if($site->medias->count() > 1)
                    <div class="bg-white rounded-lg shadow-md p-6 mb-6" x-data="carousel()" x-init="startAutoSlide()">
                        <h2 class="text-2xl font-bold text-gray-900 mb-4">Galerie photos</h2>
                        
                        <div class="relative w-full overflow-hidden rounded-lg">
                            <!-- Carousel Slides -->
                            <div class="flex transition-transform duration-500 ease-in-out" 
                                :style="`transform: translateX(-${currentIndex * 100}%)`">
                                @foreach($site->medias as $media)
                                <div class="w-full flex-shrink-0">
                                <img 
                                    src="{{ asset('storage/' . $media->url) }}" 
                                    alt="{{ $site->nom }} - Photo {{ $loop->iteration }}"
                                    class="w-full h-full object-cover"
                                    onclick="openLightbox('{{ asset('storage/' . $media->url) }}')"
                                >
                                </div>
                                @endforeach
                            </div>

                            <!-- Navigation Buttons -->
                            <button 
                                @click="prevSlide()" 
                                class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full"
                            >
                                ←
                            </button>
                            <button 
                                @click="nextSlide()" 
                                class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-black/50 text-white p-2 rounded-full"
                            >
                                →
                            </button>

                            <!-- Slide Indicators -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                @foreach($site->medias as $media)
                                <button 
                                    @click="goToSlide({{ $loop->index }})"
                                    class="w-3 h-3 rounded-full"
                                    :class="currentIndex === {{ $loop->index }} ? 'bg-white' : 'bg-gray-400'"
                                ></button>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
                </div>
                
                <div class="space-y-4">
                    <p class="text-gray-700 italic">{{ $site->description }}</p>
                    
                    <div class="flex items-center text-gray-600">
                        <i class="fas fa-map-pin mr-2 text-red-500"></i>
                        <span><strong>Localisation:</strong> {{ $site->localisation }}</span>
                    </div>
                    
                    <!-- Informations supplémentaires fictives pour l'UX -->
                    <div class="mt-4 pt-4 border-t border-gray-200 grid grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <i class="fas fa-clock mr-2 text-blue-500"></i>
                            <span>Durée moyenne: 2h</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-users mr-2 text-green-500"></i>
                            <span>Groupe max: 15</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-walking mr-2 text-orange-500"></i>
                            <span>Difficulté: Facile</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-language mr-2 text-purple-500"></i>
                            <span>Langues: FR, EN</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire de réservation -->
        <div 
            class="bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl transform hover:-translate-y-1"
            x-intersect="$el.classList.add('translate-x-0', 'opacity-100')"
            x-intersect:leave="$el.classList.remove('translate-x-0', 'opacity-100')"
            :class="{'translate-x-0 opacity-100': true, 'translate-x-full opacity-0': false}"
        >
            <div class="bg-gradient-to-r from-green-600 to-teal-800 px-6 py-4">
                <h2 class="text-xl font-bold text-white flex items-center">
                    <i class="fas fa-user-tie mr-2"></i>
                    Sélectionner un Guide
                </h2>
            </div>
            
            <div class="p-6">
                <form id="reservation-form" action="{{ route('touriste.reservations.sites.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="reservable_id" value="{{ $site->id }}">
                    <input type="hidden" name="reservable_type" value="App\Models\SiteTouristique">
                    
                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="far fa-calendar-alt mr-2 text-indigo-600"></i>
                            Dates de Réservation
                        </label>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="date" 
                                        name="date_debut" 
                                        value="{{ $dateDebut }}" 
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300"
                                        required
                                    >
                                </div>
                                <label class="text-xs text-gray-500 mt-1">Date de début</label>
                            </div>
                            <div>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <i class="fas fa-calendar-check text-gray-400"></i>
                                    </div>
                                    <input 
                                        type="date" 
                                        name="date_fin" 
                                        value="{{ $dateFin }}" 
                                        class="pl-10 w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-all duration-300"
                                        required
                                    >
                                </div>
                                <label class="text-xs text-gray-500 mt-1">Date de fin</label>
                            </div>
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium mb-2 flex items-center">
                            <i class="fas fa-user-tie mr-2 text-indigo-600"></i>
                            Guides Disponibles
                        </label>
                        
                        @if($guides->count() > 0)
                            <div class="space-y-4 max-h-80 overflow-y-auto pr-2 custom-scrollbar">
                                @foreach($guides as $guide)
                                    <div 
                                        class="border rounded-lg p-3 transition-all duration-300 cursor-pointer relative"
                                        :class="selectedGuide === {{ $guide->id }} ? 'border-indigo-500 bg-indigo-50 shadow-md' : 'border-gray-200 hover:border-indigo-300 hover:bg-gray-50'"
                                        @click="selectedGuide = {{ $guide->id }}"
                                    >
                                        <div class="flex items-center">
                                            <div class="mr-3">
                                                <div class="w-12 h-12 rounded-full bg-gray-300 flex items-center justify-center text-gray-600 overflow-hidden">
                                                    <!-- Si vous avez des avatars -->
                                                    <!-- <img src="{{ asset('images/guides/'.$guide->photo) }}" alt="{{ $guide->nom }}" class="w-full h-full object-cover"> -->
                                                    <i class="fas fa-user text-xl"></i>
                                                </div>
                                            </div>
                                            
                                            <div class="flex-1">
                                                <div class="flex items-center justify-between">
                                                    <div>
                                                        <a href="{{ route('users.show', $guide->id) }}" class="font-medium text-indigo-700 hover:text-indigo-900 hover:underline transition-colors">
                                                            {{ $guide->prenom }} {{ $guide->nom }}
                                                        </a>
                                                    </div>
                                                    <div class="flex items-center">
                                                        @php
                                                            $note = $guide->commentaires->avg('note') ?? 0;
                                                        @endphp
                                                        <div class="flex items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <i class="fas fa-star {{ $i <= $note ? 'text-yellow-400' : 'text-gray-300' }} text-sm"></i>
                                                            @endfor
                                                            <span class="ml-1 text-sm text-gray-600">
                                                                {{ $note > 0 ? number_format($note, 1) : 'Non noté' }}
                                                            </span>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <div class="text-sm text-gray-500 mt-1 flex items-center">
                                                    <i class="fas fa-language mr-1"></i>
                                                    <span>Français, Anglais</span>
                                                    <span class="mx-2">•</span>
                                                    <i class="fas fa-briefcase mr-1"></i>
                                                    <span>5 ans d'expérience</span>
                                                </div>
                                            </div>
                                            
                                            <div class="ml-2">
                                                <div 
                                                    class="w-6 h-6 rounded-full border-2 flex items-center justify-center"
                                                    :class="selectedGuide === {{ $guide->id }} ? 'border-indigo-600 bg-indigo-600' : 'border-gray-300'"
                                                >
                                                    <i class="fas fa-check text-white text-xs" x-show="selectedGuide === {{ $guide->id }}"></i>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <input 
                                            type="radio" 
                                            name="guide_id" 
                                            value="{{ $guide->id }}" 
                                            :checked="selectedGuide === {{ $guide->id }}"
                                            class="hidden" 
                                            required
                                        >
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="border border-red-200 bg-red-50 rounded-lg p-4 text-center">
                                <i class="fas fa-exclamation-circle text-red-500 text-xl mb-2"></i>
                                <p class="text-red-700">Aucun guide disponible pour ces dates.</p>
                                <p class="text-sm text-red-600 mt-1">Veuillez sélectionner d'autres dates.</p>
                            </div>
                        @endif
                    </div>

                    <button 
                        type="button"
                        @click="submitReservation"
                        class="w-full py-3 px-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-lg shadow-md hover:shadow-lg transform transition-all duration-300 hover:-translate-y-1 flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:translate-y-0"
                        :disabled="!selectedGuide || isLoading"
                    >
                        <i class="fas fa-check-circle" x-show="!isLoading"></i>
                        <i class="fas fa-circle-notch fa-spin" x-show="isLoading"></i>
                        <span x-text="isLoading ? 'Traitement en cours...' : 'Confirmer la réservation'"></span>
                    </button>
                </form>
            </div>
        </div>
    </div>
    
    <!-- Section d'informations supplémentaires -->
    <div 
        class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl"
        x-intersect="$el.classList.add('translate-y-0', 'opacity-100')"
        x-intersect:leave="$el.classList.remove('translate-y-0', 'opacity-100')"
        :class="{'translate-y-0 opacity-100': true, 'translate-y-10 opacity-0': false}"
    >
        <div class="bg-gradient-to-r from-purple-600 to-pink-800 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-info-circle mr-2"></i>
                Informations Importantes
            </h2>
        </div>
        
        <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-blue-500 flex items-start space-x-3">
                    <div class="rounded-full bg-blue-100 p-2 flex items-center justify-center text-blue-600">
                        <i class="fas fa-credit-card text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Modalités de paiement</h3>
                        <p class="text-sm text-gray-600 mt-1">Paiement sécurisé en ligne ou sur place. Un acompte de 30% peut être demandé pour confirmer votre réservation.</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-red-500 flex items-start space-x-3">
                    <div class="rounded-full bg-red-100 p-2 flex items-center justify-center text-red-600">
                        <i class="fas fa-calendar-times text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Politique d'annulation</h3>
                        <p class="text-sm text-gray-600 mt-1">Annulation gratuite jusqu'à 48h avant la visite. Passé ce délai, des frais peuvent s'appliquer.</p>
                    </div>
                </div>
                
                <div class="bg-gray-50 p-4 rounded-lg border-l-4 border-green-500 flex items-start space-x-3">
                    <div class="rounded-full bg-green-100 p-2 flex items-center justify-center text-green-600">
                        <i class="fas fa-clock text-lg"></i>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-800">Horaires de visite</h3>
                        <p class="text-sm text-gray-600 mt-1">Les visites débutent généralement entre 9h et 16h. L'horaire exact sera confirmé par votre guide.</p>
                    </div>
                </div>
            </div>
            
            <div class="mt-6 bg-blue-50 p-4 rounded-lg flex items-start space-x-3">
                <div class="text-blue-600">
                    <i class="fas fa-lightbulb text-2xl"></i>
                </div>
                <div>
                    <h3 class="font-semibold text-blue-800">Conseil pour votre visite</h3>
                    <p class="text-blue-700 mt-1">Pour une expérience optimale, prévoyez des chaussures confortables, de l'eau, et un appareil photo. Votre guide pourra vous conseiller sur les meilleures conditions de visite.</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Section FAQ -->
    <div 
        class="mt-8 bg-white rounded-lg shadow-lg overflow-hidden transition-all duration-500 hover:shadow-2xl"
        x-intersect="$el.classList.add('translate-y-0', 'opacity-100')"
        x-intersect:leave="$el.classList.remove('translate-y-0', 'opacity-100')"
        :class="{'translate-y-0 opacity-100': true, 'translate-y-10 opacity-0': false}"
    >
        <div class="bg-gradient-to-r from-amber-600 to-yellow-700 px-6 py-4">
            <h2 class="text-xl font-bold text-white flex items-center">
                <i class="fas fa-question-circle mr-2"></i>
                Questions Fréquentes
            </h2>
        </div>
        
        <div class="p-6" x-data="{
            faqs: [
                {
                    question: 'Comment choisir le bon guide ?',
                    answer: 'Consultez les évaluations et commentaires des autres visiteurs. Vous pouvez également contacter le guide directement pour discuter de vos attentes spécifiques.',
                    open: false
                },
                {
                    question: 'Puis-je modifier ma réservation ?',
                    answer: 'Oui, vous pouvez modifier votre réservation jusqu\'à 48 heures avant la date prévue, sous réserve de disponibilité.',
                    open: false
                },
                {
                    question: 'Quels sont les équipements recommandés ?',
                    answer: 'Nous recommandons des chaussures confortables, une bouteille d\'eau, un chapeau, de la crème solaire et un appareil photo.',
                    open: false
                }
            ]
        }">
            <div class="space-y-3">
                <template x-for="(faq, index) in faqs" :key="index">
                    <div class="border border-gray-200 rounded-lg transition-all duration-200" :class="faq.open ? 'bg-gray-50' : ''">
                        <button 
                            class="w-full flex justify-between items-center p-4 focus:outline-none"
                            @click="faq.open = !faq.open"
                        >
                            <span class="font-medium text-gray-800" x-text="faq.question"></span>
                            <i class="fas transition-transform duration-300" :class="faq.open ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                        </button>
                        <div 
                            class="overflow-hidden transition-all duration-300 max-h-0"
                            x-ref="answer"
                            :style="faq.open ? 'max-height: ' + $refs.answer.scrollHeight + 'px' : ''"
                        >
                            <div class="p-4 pt-0 text-gray-600" x-text="faq.answer"></div>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </div>
    
    <!-- Bouton retour -->
    <div class="mt-8 text-center">
        <a 
            href="{{ url()->previous() }}"
            class="inline-flex items-center px-5 py-2.5 bg-gray-700 hover:bg-gray-800 text-white rounded-lg shadow transition-all duration-300 hover:shadow-lg transform hover:-translate-y-1"
        >
            <i class="fas fa-arrow-left mr-2"></i>
            Retourner aux sites touristiques
        </a>
    </div>
</div>

<!-- Style personnalisé -->
<style>
    .custom-scrollbar {
        scrollbar-width: thin;
        scrollbar-color: #818cf8 #f3f4f6;
    }
    
    .custom-scrollbar::-webkit-scrollbar {
        width: 6px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-track {
        background: #f3f4f6;
        border-radius: 10px;
    }
    
    .custom-scrollbar::-webkit-scrollbar-thumb {
        background-color: #818cf8;
        border-radius: 10px;
    }
</style>

<script>
    // Carousel
    function carousel() {
        return {
            currentIndex: 0,
            slides: {{ $site->medias->count() }},
            autoSlideInterval: null,
            
            nextSlide() {
                this.currentIndex = (this.currentIndex + 1) % this.slides;
                this.resetAutoSlide();
            },
            
            prevSlide() {
                this.currentIndex = (this.currentIndex - 1 + this.slides) % this.slides;
                this.resetAutoSlide();
            },
            
            goToSlide(index) {
                this.currentIndex = index;
                this.resetAutoSlide();
            },
            
            startAutoSlide() {
                this.autoSlideInterval = setInterval(() => {
                    this.nextSlide();
                }, 3000); // Change slide every 5 seconds
            },
            
            resetAutoSlide() {
                clearInterval(this.autoSlideInterval);
                this.startAutoSlide();
            }
        }
    }
</script>
@endsection