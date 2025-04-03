@extends('layouts.app')

@section('title', 'Découvrez le Bénin | Votre prochaine destination touristique en Afrique de l\'Ouest')

@section('meta')
    <meta name="description" content="Explorez les merveilles du Bénin: sites UNESCO, parcs nationaux, villages lacustres, plages paradisiaques et culture vaudou. Circuits personnalisés avec guides locaux.">
    <meta name="keywords" content="tourisme Bénin, Ganvié, Parc Pendjari, Ouidah, circuits Bénin, voyage Afrique, culture vaudou, safari Bénin">
@endsection

@push('styles')
<style>
    /* Animation keyframes */
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out forwards;
    }
    
    .animate-pulse-slow {
        animation: pulse 3s infinite;
    }

    /* Parallax effect */
    .parallax-bg {
        background-attachment: fixed;
    }
    
    /* Custom transitions */
    .custom-transition {
        transition: all 0.5s cubic-bezier(0.25, 0.1, 0.25, 1);
    }
    
    /* Image carousel styles */
    .carousel-item {
        transition: transform 0.5s ease-in-out;
    }
    
    /* Enhanced hover effects */
    .hover-zoom:hover img {
        transform: scale(1.08);
        filter: brightness(1.1);
    }
    
    .hero-gradient-overlay {
        background: linear-gradient(to bottom, rgba(0,0,0,0.2) 0%, rgba(0,0,0,0.6) 100%);
    }
</style>
@endpush

@section('content')
    <!-- Hero Section with Video Background -->
    <section 
        x-data="{ 
            activeSlide: 0,
            slides: [
                '{{ asset('images/bg1.jpeg') }}',
                '{{ asset('images/bg2.jpeg') }}',
                '{{ asset('images/bg3.jpeg') }}',
                '{{ asset('images/bg4.jpeg') }}'
            ],
            nextSlide() {
                this.activeSlide = (this.activeSlide + 1) % this.slides.length;
            },
            prevSlide() {
                this.activeSlide = (this.activeSlide - 1 + this.slides.length) % this.slides.length;
            }
        }" 
        class="relative min-h-screen flex items-center justify-center text-white overflow-hidden">
        
        <!-- Slider Images -->
        <div class="absolute inset-0 z-0">
            <template x-for="(slide, index) in slides" :key="index">
                <div 
                    class="absolute inset-0 bg-cover bg-center transition-opacity duration-1000 ease-in-out"
                    :style="`background-image: url('${slide}')`"
                    :class="{ 'opacity-100': activeSlide === index, 'opacity-0': activeSlide !== index }">
                </div>
            </template>
        </div>
        
        <!-- Contenu de la bannière -->
        <div class="max-w-6xl mx-auto px-4 z-10 text-center">
            <h1 class="text-5xl md:text-6xl lg:text-7xl font-extrabold mb-6 leading-tight tracking-tighter">
                <span class="block text-white">Découvrez les</span>
                <span class="block text-yellow-600">Merveilles du Bénin</span>
            </h1>
            <p class="text-xl md:text-2xl mb-10 max-w-3xl mx-auto font-light text-white">
                Voyagez au cœur de l'Afrique authentique : paysages époustouflants, culture vibrante et hospitalité légendaire
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-5">
                <a href="{{ route('sites.index') }}" class="group inline-flex items-center justify-center rounded-full bg-yellow-500 hover:bg-yellow-600 px-8 py-4 font-semibold text-white transition duration-300 hover:scale-105">
                    <span class="flex items-center">
                        Explorer nos destinations
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 ml-2 group-hover:translate-x-2 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </span>
                </a>
                <a href="{{ route('sites.index') }}" class="group inline-flex items-center justify-center rounded-full bg-transparent hover:bg-white/20 px-8 py-4 font-semibold text-white border-2 border-white transition duration-300">
                    <span class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                        </svg>
                        Découvrir nos circuits
                    </span>
                </a>
            </div>
        </div>
        
        <!-- Contrôles du slider -->
        <div class="absolute bottom-10 left-0 right-0 flex justify-center space-x-3 z-10">
            <template x-for="(slide, index) in slides" :key="index">
                <button 
                    @click="activeSlide = index" 
                    :class="{ 'bg-white': activeSlide === index, 'bg-white/30': activeSlide !== index }"
                    class="w-3 h-3 rounded-full transition-colors duration-300 focus:outline-none">
                </button>
            </template>
        </div>
        
        <!-- Flèches de navigation -->
        <div class="absolute top-1/2 left-4 transform -translate-y-1/2 z-10">
            <button @click="prevSlide" class="bg-white/30 hover:bg-white/50 rounded-full p-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
        </div>
        <div class="absolute top-1/2 right-4 transform -translate-y-1/2 z-10">
            <button @click="nextSlide" class="bg-white/30 hover:bg-white/50 rounded-full p-2 transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        
        <!-- Défilement automatique toutes les 3 secondes -->
        <div x-init="setInterval(() => { nextSlide() }, 3000)"></div>
    </section>
    
    <!-- Features Section -->
    <section id="features" class="py-20 " x-data="{ shown: false }" x-intersect="shown = true">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Pourquoi nous choisir</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Une expérience authentique du Bénin</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Notre plateforme vous offre une expérience complète pour organiser votre séjour au Bénin en toute simplicité et découvrir les trésors cachés du pays.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-10">
                <template x-for="(feature, index) in [
                    {
                        icon: 'map-marked-alt',
                        title: 'Sites incontournables',
                        description: 'Découvrez les plus beaux sites touristiques du Bénin, soigneusement sélectionnés pour une expérience inoubliable.'
                    },
                    {
                        icon: 'user-tie',
                        title: 'Guides expérimentés',
                        description: 'Voyagez avec des guides locaux qualifiés qui connaissent parfaitement l\'histoire et la culture du pays.'
                    },
                    {
                        icon: 'bed',
                        title: 'Hébergements de qualité',
                        description: 'Profitez d\'un large choix d\'hébergements confortables et authentiques adaptés à tous les budgets.'
                    }
                ]" :key="index">
                    <div 
                        class="bg-white rounded-xl p-8 shadow-lg border border-gray-100 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1"
                        :class="{ 'opacity-0 translate-y-10': !shown, 'opacity-100 translate-y-0': shown }"
                        :style="`transition-delay: ${index * 150}ms`">
                        <div class="bg-green-100 text-green-700 w-20 h-20 rounded-full flex items-center justify-center mx-auto mb-6">
                            <i :class="`fas fa-${feature.icon} text-3xl`"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-3 text-center" x-text="feature.title"></h3>
                        <p class="text-gray-600 text-center" x-text="feature.description"></p>
                    </div>
                </template>
            </div>
        </div>
    </section>
    
    <!-- Popular Destinations -->
    <section class="py-20 " x-data="{ activeTab: 'all' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Explorez</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Destinations populaires</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Laissez-vous inspirer par nos sites touristiques les plus appréciés au Bénin.</p>
            </div>
            
            <!-- Category Filter Tabs -->
            <div class="flex flex-wrap justify-center gap-4 mb-12">
                <button 
                    @click="activeTab = 'all'" 
                    :class="{ 'bg-green-700 text-white': activeTab === 'all', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'all' }"
                    class="px-6 py-2 rounded-full font-medium transition">
                    Tous
                </button>
                <button 
                    @click="activeTab = 'north'" 
                    :class="{ 'bg-green-700 text-white': activeTab === 'north', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'north' }"
                    class="px-6 py-2 rounded-full font-medium transition">
                    Nord
                </button>
                <button 
                    @click="activeTab = 'south'" 
                    :class="{ 'bg-green-700 text-white': activeTab === 'south', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'south' }"
                    class="px-6 py-2 rounded-full font-medium transition">
                    Sud
                </button>
                <button 
                    @click="activeTab = 'coast'" 
                    :class="{ 'bg-green-700 text-white': activeTab === 'coast', 'bg-white text-gray-700 hover:bg-gray-100': activeTab !== 'coast' }"
                    class="px-6 py-2 rounded-full font-medium transition">
                    Côte
                </button>
            </div>
            
            <!-- Destinations Grid with Filter -->
            <div 
                x-data="{ destinations: [
                    { id: 1, name: 'Ganvié', location: 'Lac Nokoué', image: '{{ asset('images/destinations/ganvie.jpg') }}', category: 'south' },
                    { id: 2, name: 'Parc National de la Pendjari', location: 'Nord Bénin', image: '{{ asset('images/destinations/pendjari.jpg') }}', category: 'north' },
                    { id: 3, name: 'Ouidah', location: 'Côte Atlantique', image: '{{ asset('images/destinations/ouidah.jpg') }}', category: 'coast' },
                    { id: 4, name: 'Abomey', location: 'Centre Bénin', image: '{{ asset('images/destinations/abomey.jpg') }}', category: 'south' },
                    { id: 5, name: 'Tata Somba', location: 'Atacora', image: '{{ asset('images/destinations/tata-somba.jpg') }}', category: 'north' },
                    { id: 6, name: 'Grand Popo', location: 'Côte Atlantique', image: '{{ asset('images/destinations/grand-popo.jpg') }}', category: 'coast' }
                ] }"
                class="grid grid-cols-1 md:grid-cols-2 lg:grid-lg:grid-cols-3 gap-8">
                <template x-for="destination in destinations.filter(d => activeTab === 'all' || d.category === activeTab)">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="overflow-hidden h-64">
                            <img :src="destination.image" :alt="destination.name" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <div class="p-6">
                            <div class="flex items-center mb-3 text-gray-500">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <span x-text="destination.location"></span>
                            </div>
                            <h3 class="text-xl font-semibold mb-2" x-text="destination.name"></h3>
                            <div class="flex justify-between items-center mt-4">
                                <a :href="`{{ route('sites.show', '') }}/${destination.id}`" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                    Découvrir
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="text-center mt-12">
                <a href="{{ route('sites.index') }}" class="inline-flex items-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-green-700 hover:bg-green-800 md:py-4 md:text-lg md:px-10 transition">
                    Voir toutes les destinations
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Testimonials -->
    <section class="py-20" x-data="{ activeTestimonial: 0 }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Témoignages</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Ce que disent nos voyageurs</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Découvrez l'expérience de nos clients qui ont exploré les merveilles du Bénin avec nous.</p>
            </div>
            
            <div class="relative mx-auto max-w-4xl">
                <div class="relative overflow-hidden px-4 sm:px-6 lg:px-8 py-10">
                    <!-- Testimonials -->
                    <div class="relative">
                        <template x-for="(testimonial, index) in [
                            { 
                                content: 'Notre voyage au Bénin a dépassé toutes nos attentes. Des sites extraordinaires et une organisation parfaite. Merci pour cette expérience inoubliable !',
                                author: 'Sophie Dupont',
                                role: 'France',
                                avatar: '{{ asset('images/avatars/avatar-1.jpg') }}'
                            },
                            { 
                                content: 'La découverte du parc de la Pendjari était magique. Notre guide était extrêmement compétent et passionné. Je recommande sans hésitation !',
                                author: 'Marc Johnson',
                                role: 'Canada',
                                avatar: '{{ asset('images/avatars/avatar-2.jpg') }}'
                            },
                            { 
                                content: 'Le village lacustre de Ganvié est un lieu unique au monde. Une immersion totale dans la culture béninoise. Un grand merci pour cette organisation sans faille.',
                                author: 'Laura Schmidt',
                                role: 'Allemagne',
                                avatar: '{{ asset('images/avatars/avatar-3.jpg') }}'
                            }
                        ]" :key="index">
                            <div 
                                class="transition-opacity duration-500 ease-in-out flex flex-col items-center text-center"
                                :class="{ 'absolute inset-0 opacity-0': activeTestimonial !== index, 'relative opacity-100': activeTestimonial === index }">
                                
                                <div class="relative w-20 h-20 mb-6">
                                    <img :src="testimonial.avatar" :alt="testimonial.author" class="rounded-full object-cover shadow-lg">
                                    <span class="absolute -bottom-1 -right-1 bg-green-500 rounded-full p-1">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                        </svg>
                                    </span>
                                </div>
                                
                                <div class="text-2xl text-gray-600 italic mb-6 font-light" x-text="testimonial.content"></div>
                                
                                <div class="flex flex-col">
                                    <span class="font-semibold text-lg text-gray-800" x-text="testimonial.author"></span>
                                    <span class="text-gray-500" x-text="testimonial.role"></span>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>
                
                <!-- Controls -->
                <div class="flex justify-center space-x-3 mt-8">
                    <template x-for="(dot, index) in [0, 1, 2]" :key="index">
                        <button 
                            @click="activeTestimonial = index" 
                            :class="{ 'bg-green-700': activeTestimonial === index, 'bg-gray-300': activeTestimonial !== index }"
                            class="w-3 h-3 rounded-full transition-colors duration-300 focus:outline-none">
                        </button>
                    </template>
                </div>
                
                <!-- Auto rotate -->
                <div x-init="setInterval(() => { activeTestimonial = (activeTestimonial + 1) % 3 }, 7000)"></div>
            </div>
        </div>
    </section>
    
    <!-- CTA Section -->
    <section 
        class="py-20 bg-cover bg-center relative" 
        style="background-image: linear-gradient(rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.6)), url('{{ asset('images/benin-culture.jpg') }}')">
        
        <div class="absolute inset-0 bg-black opacity-50"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-4xl font-bold text-white mb-6">Prêt à découvrir le Bénin ?</h2>
                <p class="text-xl text-gray-200 mb-10">Planifiez dès maintenant votre voyage et laissez-vous enchanter par la richesse culturelle et la beauté naturelle du Bénin.</p>
                
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="" class="bg-yellow-500 hover:bg-yellow-600 text-white px-8 py-4 rounded-full font-semibold transition duration-300 ease-out hover:scale-105">
                        Contactez-nous
                    </a>
                    <a href="" class="bg-transparent border-2 border-white text-white hover:bg-white/10 px-8 py-4 rounded-full font-semibold transition duration-300">
                        Nos circuits touristiques
                    </a>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Blog Section -->
    <section class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <span class="text-green-700 font-semibold text-sm uppercase tracking-wider">Notre blog</span>
                <h2 class="text-4xl font-bold text-gray-800 mt-2 mb-4">Actualités & Conseils de voyage</h2>
                <div class="w-24 h-1 bg-yellow-500 mx-auto mb-6"></div>
                <p class="text-gray-600 max-w-2xl mx-auto text-lg">Découvrez nos derniers articles sur la culture, l'histoire et les bonnes pratiques de voyage au Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                <template x-for="(post, index) in [
                    {
                        title: 'Top 10 des plats béninois à découvrir',
                        image: '{{ asset('images/blog/benin-food.jpg') }}',
                        date: '12 mars 2023',
                        excerpt: 'Découvrez la richesse culinaire du Bénin à travers ses plats les plus emblématiques.'
                    },
                    {
                        title: 'Comment préparer votre safari au Parc Pendjari',
                        image: '{{ asset('images/blog/safari-tips.jpg') }}',
                        date: '28 février 2023',
                        excerpt: 'Nos conseils d\'experts pour réussir votre safari et observer la faune africaine dans les meilleures conditions.'
                    },
                    {
                        title: 'Festival du Vodoun: immersion dans les traditions béninoises',
                        image: '{{ asset('images/blog/vodoun-festival.jpg') }}',
                        date: '15 janvier 2023',
                        excerpt: 'Plongez dans l\'univers fascinant du vodoun et découvrez cette tradition ancestrale béninoise.'
                    }
                ]" :key="index">
                    <div class="bg-white rounded-xl overflow-hidden shadow-lg hover-zoom group">
                        <div class="overflow-hidden h-60">
                            <img :src="post.image" :alt="post.title" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                        </div>
                        <div class="p-6">
                            <div class="text-green-700 font-medium mb-2" x-text="post.date"></div>
                            <h3 class="text-xl font-bold mb-3" x-text="post.title"></h3>
                            <p class="text-gray-600 mb-4" x-text="post.excerpt"></p>
                            <a href="#" class="text-green-700 font-medium hover:text-green-800 transition flex items-center">
                                Lire la suite
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </template>
            </div>
            
            <div class="text-center mt-12">
                <a href="" class="inline-flex items-center px-6 py-3 border border-gray-300 shadow-sm text-base font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition">
                    Voir tous les articles
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                    </svg>
                </a>
            </div>
        </div>
    </section>
    
    <!-- Newsletter Section -->
    <section class="py-16 bg-green-800 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="max-w-3xl mx-auto text-center">
                <h2 class="text-3xl font-bold mb-6">Recevez nos conseils et offres exclusives</h2>
                <p class="text-lg mb-8 text-green-100">Inscrivez-vous à notre newsletter pour recevoir des conseils de voyage, des idées d'itinéraires et des offres spéciales pour découvrir le Bénin.</p>
                
                <form action="" method="POST" class="flex flex-col sm:flex-row gap-4 justify-center">
                    @csrf
                    <div class="flex-1 max-w-md">
                        <input 
                            type="email" 
                            name="email" 
                            placeholder="Votre adresse email" 
                            class="w-full px-5 py-4 rounded-full text-gray-700 focus:outline-none focus:ring-2 focus:ring-green-500"
                            required>
                    </div>
                    <button type="submit" class="bg-yellow-500 hover:bg-yellow-600 px-8 py-4 rounded-full font-semibold transition duration-300 ease-out hover:scale-105 whitespace-nowrap">
                        S'inscrire
                    </button>
                </form>
                
                <p class="text-sm mt-4 text-green-200">Nous respectons votre vie privée. Désabonnez-vous à tout moment.</p>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Additional scripts for enhanced animations and interactions
    document.addEventListener('alpine:init', () => {
        // Add any custom Alpine.js components or functions here
    });
</script>
@endpush