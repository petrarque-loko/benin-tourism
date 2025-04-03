@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-8" x-data="traditionPage()">
    <!-- Bannière avec image de fond -->
    <div class="relative h-96 rounded-xl overflow-hidden mb-8 shadow-2xl" x-ref="bannerContainer">
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 to-transparent z-10"></div>
        
        <!-- Slider pour les images -->
        <div class="relative h-full w-full">
            <template x-for="(image, index) in images" :key="index">
                <div 
                    class="absolute inset-0 transition-opacity duration-1000 ease-in-out bg-cover bg-center"
                    :style="`background-image: url('${image.url}'); opacity: ${currentImageIndex === index ? '1' : '0'}`"
                ></div>
            </template>
            
            <!-- Contrôles du slider -->
            <div class="absolute bottom-4 left-0 right-0 flex justify-center space-x-2 z-20">
                <template x-for="(image, index) in images" :key="index">
                    <button 
                        class="w-3 h-3 rounded-full transition-all duration-300 focus:outline-none"
                        :class="currentImageIndex === index ? 'bg-white scale-125' : 'bg-white/50 hover:bg-white/70'"
                        @click="currentImageIndex = index"
                    ></button>
                </template>
            </div>
            
            <!-- Boutons précédent/suivant -->
            <button 
                class="absolute left-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300"
                @click="prevImage()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                </svg>
            </button>
            <button 
                class="absolute right-4 top-1/2 -translate-y-1/2 z-20 bg-black/30 hover:bg-black/50 text-white rounded-full p-2 transition-all duration-300"
                @click="nextImage()"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>
        
        <!-- Informations principales superposées sur la bannière -->
        <div class="absolute bottom-0 left-0 right-0 p-6 z-20 text-white">
            <div class="flex items-center mb-2">
                <span class="inline-block px-3 py-1 bg-indigo-600 rounded-full text-sm font-medium mr-2">
                    {{ $tradition->categorie->nom }}
                </span>
                <span class="text-sm flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    {{ $tradition->created_at->format('d/m/Y') }}
                </span>
            </div>
            <h1 class="text-4xl font-bold mb-2 tracking-tight">{{ $tradition->titre }}</h1>
            <p class="text-xl opacity-90">{{ $tradition->resume }}</p>
        </div>
    </div>
    
    <!-- Contenu principal -->
    <div class="flex flex-col lg:flex-row gap-10">
        <!-- Contenu textuel -->
        <div class="lg:w-2/3 bg-white rounded-xl shadow-lg p-6 transition-all duration-500 hover:shadow-xl">
            <div class="prose prose-lg max-w-none" x-show="!isReading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <h2 class="text-2xl font-bold mb-4 text-gray-800 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                    </svg>
                    À propos de cette tradition
                </h2>
                
                <!-- Résumé avec animation de déroulement -->
                <div class="mb-6">
                    <p class="text-gray-700">{{ $tradition->resume }}</p>
                </div>
                
                <!-- Bouton "Lire plus" -->
                <button 
                    @click="isReading = true" 
                    class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    Lire l'article complet
                </button>
            </div>
            
            <!-- Contenu complet -->
            <div x-show="isReading" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        Article complet
                    </h2>
                    <button 
                        @click="isReading = false" 
                        class="inline-flex items-center px-3 py-1 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                        </svg>
                        Retour
                    </button>
                </div>
                
                <div class="prose prose-lg max-w-none text-gray-700">
                    {!! $tradition->contenu !!}
                </div>
                
                <!-- Bouton pour partager -->
                <div class="mt-8 flex flex-wrap gap-2">
                    <button 
                        @click="shareOnSocial('facebook')" 
                        class="flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M9 8h-3v4h3v12h5v-12h3.642l.358-4h-4v-1.667c0-.955.192-1.333 1.115-1.333h2.885v-5h-3.808c-3.596 0-5.192 1.583-5.192 4.615v3.385z" />
                        </svg>
                        Partager
                    </button>
                    <button 
                        @click="shareOnSocial('twitter')" 
                        class="flex items-center px-4 py-2 bg-sky-500 text-white rounded-lg hover:bg-sky-600 transition-colors duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z" />
                        </svg>
                        Tweeter
                    </button>
                    <button 
                        @click="shareOnSocial('whatsapp')" 
                        class="flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-300"
                    >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z" />
                        </svg>
                        WhatsApp
                    </button>
                </div>
            </div>
            <!-- Suggestions de lecture -->
            <div class="mt-8">
                    <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Suggestions de lecture
                    </h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @foreach ($traditionsSuggestions ?? [] as $suggestion)
                        <a href="{{ route('traditions.show', $suggestion->id) }}" class="flex bg-white rounded-lg overflow-hidden shadow hover:shadow-md transition-shadow duration-300">
                            <div class="w-1/3 bg-cover bg-center" style="background-image: url('{{ $suggestion->medias->where('type', 'image')->first()->url ?? '/images/default-tradition.jpg' }}')"></div>
                            <div class="w-2/3 p-4">
                                <span class="inline-block px-2 py-1 bg-indigo-100 text-indigo-800 rounded-full text-xs font-medium mb-2">
                                    {{ $suggestion->categorie->nom }}
                                </span>
                                <h4 class="font-bold text-gray-800 mb-1 line-clamp-1">{{ $suggestion->titre }}</h4>
                                <p class="text-sm text-gray-600 line-clamp-2">{{ $suggestion->resume }}</p>
                            </div>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div><br>
        
        <!-- Colonne horizontale -->
        <div class="w-full space-x-6 flex flex-row">
            <!-- Carte d'information -->
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-500 hover:shadow-xl flex-1">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Informations
                </h3>
                <div class="space-y-3">
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                        </svg>
                        <span class="text-gray-700">Publié par <span class="font-medium">Administrateur</span></span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        <span class="text-gray-700">{{ $tradition->created_at->format('d/m/Y') }}</span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z" />
                        </svg>
                        <span class="text-gray-700">Catégorie: <span class="font-medium">{{ $tradition->categorie->nom }}</span></span>
                    </div>
                    <div class="flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                        </svg>
                        <span class="text-gray-700">Vues: <span class="font-medium">{{ $tradition->views ?? 0 }}</span></span>
                    </div>
                </div>
            </div>
            
            <!-- Vidéo si disponible -->
            @if ($video)
            <div class="bg-white rounded-xl shadow-lg p-6 transition-all duration-500 hover:shadow-xl flex-1">
                <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    Vidéo
                </h3>
                <div class="aspect-w-16 aspect-h-9 rounded-lg overflow-hidden">
                    <iframe src="{{ $video->url }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen class="w-full h-full"></iframe>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>




<script>
    function traditionPage() {
        return {
            isReading: false,
            currentImageIndex: 0,
            images: @json($images->map(function($img) { return ['url' => $img->url]; })),
            
            nextImage() {
                this.currentImageIndex = (this.currentImageIndex + 1) % this.images.length;
            },
            
            prevImage() {
                this.currentImageIndex = (this.currentImageIndex - 1 + this.images.length) % this.images.length;
            },
            
            shareOnSocial(platform) {
                const url = window.location.href;
                const title = "{{ $tradition->titre }}";
                
                let shareUrl = '';
                
                switch(platform) {
                    case 'facebook':
                        shareUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(url)}`;
                        break;
                    case 'twitter':
                        shareUrl = `https://twitter.com/intent/tweet?url=${encodeURIComponent(url)}&text=${encodeURIComponent(title)}`;
                        break;
                    case 'whatsapp':
                        shareUrl = `https://wa.me/?text=${encodeURIComponent(title + ' ' + url)}`;
                        break;
                }
                
                window.open(shareUrl, '_blank', 'width=600,height=400');
            },
            
            init() {
                // Initialisation du slider automatique
                setInterval(() => {
                    if (!this.$refs.bannerContainer.matches(':hover')) {
                        this.nextImage();
                    }
                }, 5000);
            }
        }
    }
</script>
@endsection