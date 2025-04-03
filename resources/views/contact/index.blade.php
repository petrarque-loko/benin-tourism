<!-- resources/views/contact/index.blade.php -->
@extends('layouts.app')

@section('title', 'Contactez-nous | Tourisme Bénin')

@section('content')
<div class="bg-gradient-to-b ">
    <!-- En-tête de la page contact -->
    <div class="relative overflow-hidden bg-cover bg-center h-96"      style="background-image: url('{{ asset('images/bg2.jpeg') }}'); background-size: cover; background-position: center;">
    
        <div class="absolute inset-0 bg-black opacity-50"></div>
        <div class="container mx-auto px-6 relative z-10 flex items-center h-full">
            <div x-data="{ show: false }" x-init="setTimeout(() => show = true, 500)" class="w-full text-center">
                <h1 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-700" 
                    x-transition:enter-start="opacity-0 transform translate-y-10" 
                    x-transition:enter-end="opacity-100 transform translate-y-0"
                    class="text-5xl md:text-7xl font-bold text-white mb-4"
                >
                    Contactez-Nous
                </h1>
                <p 
                    x-show="show" 
                    x-transition:enter="transition ease-out duration-700 delay-300" 
                    x-transition:enter-start="opacity-0 transform translate-y-10" 
                    x-transition:enter-end="opacity-100 transform translate-y-0" 
                    class="text-xl md:text-2xl text-white"
                >
                    Nous sommes là pour faire de votre voyage au Bénin une expérience inoubliable
                </p>
            </div>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="container mx-auto px-6 py-16">
        <div class="flex flex-wrap -mx-4">
            <!-- Informations de contact -->
            <div class="w-full lg:w-1/3 px-4 mb-12 lg:mb-0">
                <div x-data="{ shown: false }" 
                     x-init="setTimeout(() => shown = true, 800)"
                     x-show="shown"
                     x-transition:enter="transition ease-out duration-700"
                     x-transition:enter-start="opacity-0 transform translate-x-10"
                     x-transition:enter-end="opacity-100 transform translate-x-0" 
                     class="bg-white rounded-lg shadow-xl overflow-hidden">
                    <div class="bg-teal-600 p-6">
                        <h2 class="text-2xl font-bold text-white">Nos Coordonnées</h2>
                    </div>
                    <div class="p-6 space-y-6">
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Adresse</h4>
                                <p class="mt-1 text-gray-600">123 Avenue du Tourisme<br>Cotonou, Bénin</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Téléphone</h4>
                                <p class="mt-1 text-gray-600">+229 67 26 53 65</p>
                            </div>
                        </div>
                        <div class="flex items-start">
                            <div class="flex-shrink-0 mt-1">
                                <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <h4 class="text-lg font-medium text-gray-900">Email</h4>
                                <p class="mt-1 text-gray-600">contact@tourisme-benin.com</p>
                            </div>
                        </div>
                    </div>
                    <div class="p-6 bg-gray-50">
                        <h3 class="text-xl font-semibold text-gray-900 mb-4">Heures d'ouverture</h3>
                        <div class="space-y-2">
                            <div class="flex justify-between">
                                <span class="text-gray-600">Lundi - Vendredi:</span>
                                <span class="font-medium">8h - 18h</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Samedi:</span>
                                <span class="font-medium">9h - 16h</span>
                            </div>
                            <div class="flex justify-between">
                                <span class="text-gray-600">Dimanche:</span>
                                <span class="font-medium">Fermé</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Formulaire de contact -->
            <div class="w-full lg:w-2/3 px-4">
                <div x-data="{ 
                    formShown: false,
                    formSubmitted: false,
                    formData: {
                        nom: '',
                        email: '',
                        telephone: '',
                        sujet: '',
                        message: ''
                    },
                    errors: {},
                    validateForm() {
                        this.errors = {};
                        if (!this.formData.nom) this.errors.nom = 'Le nom est requis';
                        if (!this.formData.email) this.errors.email = 'L\'email est requis';
                        else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(this.formData.email)) this.errors.email = 'Email invalide';
                        if (!this.formData.sujet) this.errors.sujet = 'Le sujet est requis';
                        if (!this.formData.message) this.errors.message = 'Le message est requis';
                        return Object.keys(this.errors).length === 0;
                    },
                    submitForm() {
                        if (this.validateForm()) {
                            this.formSubmitted = true;
                            // En réalité, on soumettrait le formulaire au serveur ici
                            setTimeout(() => {
                                // Simulation de la réponse du serveur
                                document.getElementById('contactForm').submit();
                            }, 1500);
                        }
                    }
                }" 
                x-init="setTimeout(() => formShown = true, 1000)"
                x-show="formShown"
                x-transition:enter="transition ease-out duration-700"
                x-transition:enter-start="opacity-0 transform translate-y-10"
                x-transition:enter-end="opacity-100 transform translate-y-0"
                class="bg-white rounded-lg shadow-xl overflow-hidden">
                    <div class="bg-teal-600 p-6">
                        <h2 class="text-2xl font-bold text-white">Envoyez-nous un message</h2>
                    </div>

                    <form id="contactForm" action="{{ route('contact.submit') }}" method="POST" class="p-6 space-y-6" @submit.prevent="submitForm()">
                        @csrf
                        <div x-show="formSubmitted" class="mb-6 p-4 bg-yellow-100 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-6 h-6 text-yellow-600 mr-3 animate-spin" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                <span class="text-yellow-800 font-medium">Envoi en cours...</span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="nom" class="block text-sm font-medium text-gray-700 mb-1">Nom complet <span class="text-red-600">*</span></label>
                                <input 
                                    type="text" 
                                    id="nom" 
                                    name="nom" 
                                    x-model="formData.nom"
                                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                    :class="{'border-red-500': errors.nom}"
                                >
                                <span x-show="errors.nom" x-text="errors.nom" class="text-sm text-red-600 mt-1"></span>
                            </div>

                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-600">*</span></label>
                                <input 
                                    type="email" 
                                    id="email" 
                                    name="email" 
                                    x-model="formData.email"
                                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                    :class="{'border-red-500': errors.email}"
                                >
                                <span x-show="errors.email" x-text="errors.email" class="text-sm text-red-600 mt-1"></span>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="telephone" class="block text-sm font-medium text-gray-700 mb-1">Téléphone</label>
                                <input 
                                    type="tel" 
                                    id="telephone" 
                                    name="telephone" 
                                    x-model="formData.telephone"
                                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                >
                            </div>

                            <div>
                                <label for="sujet" class="block text-sm font-medium text-gray-700 mb-1">Sujet <span class="text-red-600">*</span></label>
                                <select 
                                    id="sujet" 
                                    name="sujet" 
                                    x-model="formData.sujet"
                                    class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                    :class="{'border-red-500': errors.sujet}"
                                >
                                    <option value="">Sélectionnez un sujet</option>
                                    <option value="Reservation">Réservation de circuit</option>
                                    <option value="Devis">Demande de devis</option>
                                    <option value="Information">Renseignements</option>
                                    <option value="Autre">Autre</option>
                                </select>
                                <span x-show="errors.sujet" x-text="errors.sujet" class="text-sm text-red-600 mt-1"></span>
                            </div>
                        </div>

                        <div>
                            <label for="message" class="block text-sm font-medium text-gray-700 mb-1">Message <span class="text-red-600">*</span></label>
                            <textarea 
                                id="message" 
                                name="message" 
                                rows="5" 
                                x-model="formData.message"
                                class="w-full px-4 py-2 border rounded-md focus:ring-2 focus:ring-teal-500 focus:border-teal-500 transition-all duration-300"
                                :class="{'border-red-500': errors.message}"
                            ></textarea>
                            <span x-show="errors.message" x-text="errors.message" class="text-sm text-red-600 mt-1"></span>
                        </div>

                        <div>
                            <button 
                                type="submit" 
                                :disabled="formSubmitted"
                                class="w-full md:w-auto px-6 py-3 text-white font-medium bg-teal-600 rounded-md shadow-sm hover:bg-teal-700 focus:outline-none focus:ring-2 focus:ring-teal-500 focus:ring-offset-2 transition-all duration-300 transform hover:-translate-y-1"
                                :class="{'opacity-75 cursor-not-allowed': formSubmitted}"
                            >
                                <span x-show="!formSubmitted">Envoyer le message</span>
                                <span x-show="formSubmitted">Envoi en cours...</span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Section carte -->
        <div class="mt-16">
            <div x-data="{ mapShown: false }" 
                 x-intersect="mapShown = true"
                 x-show="mapShown"
                 x-transition:enter="transition ease-out duration-700"
                 x-transition:enter-start="opacity-0"
                 x-transition:enter-end="opacity-100" 
                 class="bg-white  rounded-lg shadow-xl overflow-hidden">
                <div class="p-6 bg-teal-600">
                    <h2 class="text-2xl font-bold text-white">Nous trouver</h2>
                </div>
                <div class="h-96 bg-gray-200">
                    <!-- Ici vous intégreriez une vraie carte Google Maps ou similaire -->
                    <div class="h-full flex items-center justify-center">
                        <div class="text-center">
                            <svg class="w-16 h-16 mx-auto text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <p class="mt-3 text-lg text-gray-600">Carte interactive à intégrer ici</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section FAQ -->
    <div class=" py-16">
        <div class="container mx-auto px-6">
            <div class="text-center mb-12">
                <h2 class="text-3xl font-bold text-gray-900">Questions fréquentes</h2>
                <p class="mt-4 text-xl text-gray-600">Tout ce que vous devez savoir avant de nous contacter</p>
            </div>

            <div class="max-w-3xl mx-auto">
                <div x-data="{ faqItems: [
                    { 
                        question: 'Comment réserver un circuit touristique ?',
                        answer: 'Vous pouvez réserver un circuit directement en ligne via notre plateforme ou nous contacter par téléphone ou email. Notre équipe est disponible pour vous aider à planifier votre voyage sur mesure.',
                        open: false 
                    },
                    { 
                        question: 'Quels sont les meilleurs moments pour visiter le Bénin ?',
                        answer: 'La saison sèche, de novembre à mars, est généralement considérée comme la meilleure période pour visiter le Bénin. Le climat est agréable et les routes sont plus facilement praticables.',
                        open: false 
                    },
                    { 
                        question: 'Faut-il un visa pour visiter le Bénin ?',
                        answer: 'Oui, la plupart des visiteurs ont besoin d\'un visa pour entrer au Bénin. Vous pouvez obtenir un e-visa en ligne avant votre voyage ou à l\'arrivée à l\'aéroport international de Cotonou.',
                        open: false 
                    },
                    { 
                        question: 'Proposez-vous des circuits personnalisés ?',
                        answer: 'Absolument ! Nous sommes spécialisés dans la création d\'itinéraires sur mesure adaptés à vos intérêts, votre budget et votre emploi du temps. Contactez-nous pour discuter de vos préférences.',
                        open: false 
                    }
                ]}" class="space-y-4">
                    <template x-for="(item, index) in faqItems" :key="index">
                        <div 
                            x-data="{ inView: false }" 
                            x-intersect="setTimeout(() => inView = true, index * 200)"
                            x-show="inView"
                            x-transition:enter="transition ease-out duration-500"
                            x-transition:enter-start="opacity-0 transform translate-y-4"
                            x-transition:enter-end="opacity-100 transform translate-y-0"
                            class="border border-gray-200 rounded-lg overflow-hidden"
                        >
                            <button 
                                @click="item.open = !item.open" 
                                class="flex justify-between items-center w-full p-4 text-left bg-white hover:bg-gray-50 transition-colors duration-200"
                            >
                                <span class="text-lg font-medium text-gray-900" x-text="item.question"></span>
                                <svg 
                                    class="w-5 h-5 text-gray-500 transition-transform duration-300" 
                                    :class="{'rotate-180': item.open}"
                                    fill="none" 
                                    stroke="currentColor" 
                                    viewBox="0 0 24 24"
                                >
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                </svg>
                            </button>
                            <div 
                                x-show="item.open" 
                                x-transition:enter="transition ease-out duration-300"
                                x-transition:enter-start="opacity-0 transform -translate-y-2"
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                x-transition:leave="transition ease-in duration-200"
                                x-transition:leave-start="opacity-100 transform translate-y-0"
                                x-transition:leave-end="opacity-0 transform -translate-y-2"
                                class="p-4 bg-gray-50 border-t border-gray-200"
                            >
                                <p class="text-gray-700" x-text="item.answer"></p>
                            </div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Vous pouvez ajouter ici du JavaScript supplémentaire spécifique à la page de contact
    document.addEventListener('alpine:init', () => {
        // Extensions Alpine.js supplémentaires si nécessaire
    });
</script>
@endpush