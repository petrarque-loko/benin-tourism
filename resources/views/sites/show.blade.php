@extends('layouts.app')

@section('content')
<div x-data="{
    activeSection: 'description',
    activeImageIndex: 0,
    showDatePicker: false,
    dates: { start: '', end: '' }
}" class=" min-h-screen">
    <meta name="commentable-type" content="site">
    <meta name="commentable-id" content="{{ $site->id }}">
    @auth
    <meta name="user-id" content="{{ auth()->id() }}">
    <meta name="user-role" content="{{ auth()->user()->role }}">
    @endauth

    <!-- Hero Section -->
    <div class="relative h-[50vh] md:h-[60vh] lg:h-[70vh] overflow-hidden">
        @if($site->medias->count() > 0)
            <img src="{{ asset('storage/' . $site->medias->first()->url) }}"
                 alt="{{ $site->nom }}"
                 class="w-full h-full object-cover object-center transition-transform duration-700 hover:scale-105"
                 loading="eager">
        @else
            <div class="w-full h-full bg-gradient-to-r from-blue-600 to-indigo-700"></div>
        @endif
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>
        <div class="absolute inset-0 flex flex-col justify-end p-6 md:p-12">
            <div class="bg-white/90 dark:bg-gray-800/90 backdrop-blur-sm rounded-lg p-6 w-full md:w-2/3 lg:w-1/2 shadow-lg transform transition-all duration-500 hover:shadow-xl">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-2">{{ $site->nom }}</h1>
                <div class="flex items-center mt-2 mb-4">
                    <span class="bg-indigo-100 dark:bg-indigo-900 text-indigo-800 dark:text-indigo-200 px-3 py-1 rounded-full text-sm font-medium">
                        {{ $site->categorie->nom }}
                    </span>
                </div>
                <p class="mt-2 flex items-center text-gray-700 dark:text-gray-300 mb-4">
                    <svg class="w-5 h-5 mr-2 text-gray-600 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                    {{ $site->localisation }}
                </p>
                <button @click="showDatePicker = true"
                        class="inline-flex items-center px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors duration-300 shadow-md hover:shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 transform hover:-translate-y-1">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                    Réserver maintenant
                </button>
            </div>
        </div>
    </div>

    <!-- Navigation Tabs -->
    <div class="sticky top-0 z-10 bg-white shadow-md">
        <div class="max-w-6xl mx-auto px-4">
            <nav class="flex space-x-1 overflow-x-auto scrollbar-hide" aria-label="Tabs">
                <button @click="activeSection = 'description'"
                        :class="{'border-blue-500 text-blue-600': activeSection === 'description', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'description'}"
                        class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Description
                </button>
                <button @click="activeSection = 'galerie'"
                        :class="{'border-blue-500 text-blue-600': activeSection === 'galerie', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'galerie'}"
                        class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Galerie
                </button>
                <button @click="activeSection = 'avis'"
                        :class="{'border-blue-500 text-blue-600': activeSection === 'avis', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'avis'}"
                        class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Avis
                </button>
                <button @click="activeSection = 'localisation'"
                        :class="{'border-blue-500 text-blue-600': activeSection === 'localisation', 'border-transparent text-gray-500 hover:text-gray-700': activeSection !== 'localisation'}"
                        class="flex-shrink-0 px-4 py-4 text-sm sm:text-base font-medium border-b-2 focus:outline-none">
                    Localisation
                </button>
            </nav>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-4 py-8">
        <!-- Description Section -->
        <div x-show="activeSection === 'description'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-4 text-gray-800">À propos de ce site</h2>
            <div class="prose max-w-none text-gray-600">
                {!! nl2br(e($site->description)) !!}
            </div>
        </div>

        <!-- Galerie Section -->
        <div x-show="activeSection === 'galerie'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Galerie photos</h2>
            @if($site->medias->count() > 0)
                <div class="relative">
                    <!-- Main Image Preview -->
                    <div class="relative h-96 mb-4 rounded-lg overflow-hidden shadow-lg">
                        <template x-for="(image, index) in {{ json_encode($site->medias->map(fn($media) => asset('storage/' . $media->url))->toArray()) }}" :key="index">
                            <img :src="image"
                                 x-show="activeImageIndex === index"
                                 x-transition:enter="transition ease-out duration-300"
                                 x-transition:enter-start="opacity-0 transform scale-95"
                                 x-transition:enter-end="opacity-100 transform scale-100"
                                 class="w-full h-full object-cover"
                                 alt="{{ $site->nom }}">
                        </template>
                        <!-- Navigation Arrows -->
                        <button @click="activeImageIndex = (activeImageIndex - 1 + {{ $site->medias->count() }}) % {{ $site->medias->count() }}"
                                class="absolute left-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>
                        <button @click="activeImageIndex = (activeImageIndex + 1) % {{ $site->medias->count() }}"
                                class="absolute right-4 top-1/2 transform -translate-y-1/2 bg-black bg-opacity-50 hover:bg-opacity-75 rounded-full p-2 text-white transition duration-300">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                    <!-- Thumbnails -->
                    <div class="grid grid-cols-4 sm:grid-cols-6 md:grid-cols-8 gap-2">
                        @foreach($site->medias as $index => $media)
                            <div @click="activeImageIndex = {{ $index }}"
                                 :class="{'ring-2 ring-blue-500': activeImageIndex === {{ $index }}}"
                                 class="h-16 rounded-md overflow-hidden cursor-pointer hover:opacity-90 transition duration-300">
                                <img src="{{ asset('storage/' . $media->url) }}"
                                     class="w-full h-full object-cover"
                                     alt="Thumbnail">
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="text-center py-12 text-gray-500">Aucune image disponible pour ce site.</div>
            @endif
        </div>

        <!-- Avis Section -->
        <div x-show="activeSection === 'avis'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Avis et commentaires</h2>
            <div x-data="commentairesComponent()" x-init="init()" class="commentaires-section">
                <!-- Formulaire d'ajout de commentaire -->
                <div x-show="canComment" class="bg-gray-50 p-4 rounded shadow mb-4">
                    <h3 class="text-lg font-bold mb-2">Ajouter un commentaire</h3>
                    <div class="mb-3">
                        <label class="block mb-1">Note</label>
                        <div class="flex space-x-1">
                            <template x-for="i in 5">
                                <button @click="newComment.note = i"
                                        class="text-2xl focus:outline-none"
                                        :class="newComment.note >= i ? 'text-yellow-400' : 'text-gray-300'">★</button>
                            </template>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="block mb-1">Votre commentaire</label>
                        <textarea x-model="newComment.contenu"
                                  class="w-full p-2 border rounded"
                                  rows="3"
                                  placeholder="Partagez votre expérience..."></textarea>
                    </div>
                    <div class="text-red-500 mb-2" x-text="errorMessage"></div>
                    <button @click="submitComment()"
                            class="bg-blue-500 text-white py-2 px-4 rounded hover:bg-blue-600"
                            :disabled="submitting">
                        <span x-show="submitting">Envoi en cours...</span>
                        <span x-show="!submitting">Publier</span>
                    </button>
                </div>
                <!-- Message si l'utilisateur ne peut pas commenter -->
                <div x-show="!canComment && isLoggedIn && !userHasCommented" class="bg-yellow-100 p-4 rounded mb-4">
                    <p>Vous devez avoir une réservation terminée incluant ce site pour pouvoir le commenter.</p>
                </div>
                <!-- Liste des commentaires -->
                <div class="mb-4">
                    <div class="flex items-center mb-2">
                        <div class="text-2xl font-bold mr-2" x-text="noteMoyenne"></div>
                        <div class="flex">
                            <template x-for="i in 5">
                                <div class="text-xl" :class="noteMoyenne >= i ? 'text-yellow-400' : 'text-gray-300'">★</div>
                            </template>
                        </div>
                        <div class="ml-2 text-gray-600" x-text="`(${totalCommentaires} avis)`"></div>
                    </div>
                </div>
                <div class="space-y-4">
                    <template x-for="(comment, index) in commentaires" :key="comment.id">
                        <div class="bg-gray-50 p-4 rounded shadow">
                            <div class="flex justify-between">
                                <div class="font-bold" x-text="`${comment.user.prenom} ${comment.user.nom}`"></div>
                                <div class="text-gray-500" x-text="formatDate(comment.created_at)"></div>
                            </div>
                            <div class="flex my-1">
                                <template x-for="i in 5">
                                    <div class="text-xl" :class="comment.note >= i ? 'text-yellow-400' : 'text-gray-300'">★</div>
                                </template>
                            </div>
                            <!-- Mode lecture -->
                            <div x-show="!comment.editing" class="mt-2">
                                <p x-text="comment.contenu"></p>
                                <div x-show="userId === comment.user_id" class="mt-2 flex space-x-2">
                                    <button @click="editComment(index)"
                                            class="text-blue-500 hover:text-blue-700 p-1 rounded hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                        </svg>
                                    </button>
                                    <button @click="deleteComment(comment.id)"
                                            class="text-red-500 hover:text-red-700 p-1 rounded hover:bg-gray-100">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                    </button>
                                </div>
                                <div x-show="isAdmin && userId !== comment.user_id" class="mt-2">
                                    <button @click="toggleVisibility(comment.id, index)"
                                            :class="comment.is_hidden ? 'text-green-500 hover:text-green-700' : 'text-red-500 hover:text-red-700'"
                                            class="p-1 rounded hover:bg-gray-100">
                                        <svg x-show="comment.is_hidden" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                        </svg>
                                        <svg x-show="!comment.is_hidden" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <!-- Mode édition -->
                            <div x-show="comment.editing" class="mt-2">
                                <div class="mb-2">
                                    <label class="block mb-1">Note</label>
                                    <div class="flex space-x-1">
                                        <template x-for="i in 5">
                                            <button @click="comment.editNote = i"
                                                    class="text-2xl focus:outline-none"
                                                    :class="comment.editNote >= i ? 'text-yellow-400' : 'text-gray-300'">★</button>
                                        </template>
                                    </div>
                                </div>
                                <textarea x-model="comment.editContenu"
                                          class="w-full p-2 border rounded mb-2"
                                          rows="3"></textarea>
                                <div class="flex space-x-2">
                                    <button @click="updateComment(comment.id, index)"
                                            class="bg-blue-500 text-white py-1 px-3 rounded hover:bg-blue-600">Enregistrer</button>
                                    <button @click="cancelEdit(index)"
                                            class="bg-gray-500 text-white py-1 px-3 rounded hover:bg-gray-600">Annuler</button>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="commentaires.length === 0" class="text-gray-500 text-center py-4">
                        Aucun commentaire pour le moment. Soyez le premier à partager votre expérience !
                    </div>
                </div>
            </div>
        </div>

        <!-- Localisation Section -->
        <div x-show="activeSection === 'localisation'"
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0 transform translate-y-4"
             x-transition:enter-end="opacity-100 transform translate-y-0"
             class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Localisation</h2>
            <div class="bg-gray-50 rounded-lg h-96 overflow-hidden">
                <div id="map" class="w-full h-full"></div>
            </div>
            <div class="mt-4">
                <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($site->localisation) }}"
                   target="_blank"
                   class="flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 w-full">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7"></path>
                    </svg>
                    Itinéraire
                </a>
            </div>
        </div>
    </div>

    <!-- Modal de sélection de dates -->
    <div x-show="showDatePicker"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 overflow-y-auto">
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="showDatePicker = false"></div>
            <div x-show="showDatePicker"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform translate-y-4 sm:scale-95"
                 x-transition:enter-end="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 transform translate-y-0 sm:scale-100"
                 x-transition:leave-end="opacity-0 transform translate-y-4 sm:scale-95"
                 class="inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle bg-white rounded-2xl shadow-xl transform transition-all">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">Sélectionnez vos dates</h3>
                <form method="GET" action="{{ route('touriste.reservations.sites.create', $site->id) }}">
                    <div class="mb-4">
                        <label for="date_debut" class="block mb-2">Date de début</label>
                        <input type="date" name="date_debut" id="date_debut" x-model="dates.start" class="w-full border rounded p-2" required>
                    </div>
                    <div class="mb-4">
                        <label for="date_fin" class="block mb-2">Date de fin</label>
                        <input type="date" name="date_fin" id="date_fin" x-model="dates.end" class="w-full border rounded p-2" required>
                    </div>
                    <div class="flex justify-between">
                        <button type="button" @click="showDatePicker = false" class="bg-gray-300 px-4 py-2 rounded">Annuler</button>
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Continuer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://maps.googleapis.com/maps/api/js?key=VOTRE_CLE_API_GOOGLE_MAPS&callback=initMap" defer></script>
<script>
    function initMap() {
        const locationName = "{{ $site->localisation }}";
        const geocoder = new google.maps.Geocoder();
        geocoder.geocode({ address: locationName }, function(results, status) {
            if (status === "OK") {
                const map = new google.maps.Map(document.getElementById("map"), {
                    center: results[0].geometry.location,
                    zoom: 14,
                    mapTypeControl: false,
                    streetViewControl: false
                });
                new google.maps.Marker({
                    map: map,
                    position: results[0].geometry.location,
                    animation: google.maps.Animation.DROP,
                    title: "{{ $site->nom }}"
                });
            } else {
                document.getElementById("map").innerHTML = "<p class='p-4 text-center text-gray-500'>Carte non disponible</p>";
                console.error("Geocode error: " + status);
            }
        });
    }

    function commentairesComponent() {
        return {
            commentaires: [],
            noteMoyenne: 0,
            totalCommentaires: 0,
            canComment: false,
            isLoggedIn: false,
            userId: null,
            isAdmin: false,
            userHasCommented: false,
            submitting: false,
            errorMessage: '',
            commentableType: '',
            commentableId: null,
            newComment: { contenu: '', note: 0 },

            init() {
                this.commentableType = document.querySelector('meta[name="commentable-type"]').getAttribute('content');
                this.commentableId = document.querySelector('meta[name="commentable-id"]').getAttribute('content');
                const userMetaElement = document.querySelector('meta[name="user-id"]');
                this.isLoggedIn = userMetaElement !== null;
                if (this.isLoggedIn) {
                    this.userId = parseInt(userMetaElement.getAttribute('content'));
                    this.isAdmin = document.querySelector('meta[name="user-role"]').getAttribute('content') === 'Administrateur';
                }
                this.loadCommentaires();
                if (this.isLoggedIn) this.checkUserCanComment();
            },

            loadCommentaires() {
                fetch(`/commentaires?commentable_type=${this.commentableType}&commentable_id=${this.commentableId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.commentaires = data.commentaires.map(comment => ({
                                ...comment,
                                editing: false,
                                editContenu: comment.contenu,
                                editNote: comment.note
                            }));
                            this.noteMoyenne = data.note_moyenne;
                            this.totalCommentaires = data.total_commentaires;
                            if (this.isLoggedIn) {
                                this.userHasCommented = this.commentaires.some(comment => comment.user_id === parseInt(this.userId));
                                this.canComment = !this.userHasCommented;
                            }
                        }
                    })
                    .catch(error => console.error('Erreur chargement commentaires:', error));
            },

            checkUserCanComment() {
                fetch(`/commentaires/can-comment?commentable_type=${this.commentableType}&commentable_id=${this.commentableId}`, {
                    headers: { 'Authorization': `Bearer ${this.getToken()}` }
                })
                    .then(response => response.json())
                    .then(data => this.canComment = data.can_comment && !this.userHasCommented)
                    .catch(error => console.error('Erreur vérification droits:', error));
            },

            submitComment() {
                if (this.newComment.contenu.trim() === '') {
                    this.errorMessage = 'Veuillez écrire un commentaire';
                    return;
                }
                if (this.newComment.note === 0) {
                    this.errorMessage = 'Veuillez donner une note';
                    return;
                }
                this.submitting = true;
                this.errorMessage = '';
                fetch('/commentaires', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({
                        contenu: this.newComment.contenu,
                        note: this.newComment.note,
                        commentable_type: this.commentableType,
                        commentable_id: this.commentableId
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        this.submitting = false;
                        if (data.success) {
                            this.commentaires.unshift({
                                ...data.commentaire,
                                editing: false,
                                editContenu: data.commentaire.contenu,
                                editNote: data.commentaire.note
                            });
                            this.userHasCommented = true;
                            this.canComment = false;
                            this.newComment.contenu = '';
                            this.newComment.note = 0;
                            this.totalCommentaires++;
                            this.updateAverageRating();
                        } else {
                            this.errorMessage = data.message;
                        }
                    })
                    .catch(error => {
                        console.error('Erreur ajout commentaire:', error);
                        this.submitting = false;
                        this.errorMessage = 'Erreur lors de l\'ajout du commentaire';
                    });
            },

            editComment(index) { this.commentaires[index].editing = true; },

            cancelEdit(index) {
                this.commentaires[index].editing = false;
                this.commentaires[index].editContenu = this.commentaires[index].contenu;
                this.commentaires[index].editNote = this.commentaires[index].note;
            },

            updateComment(commentId, index) {
                const comment = this.commentaires[index];
                if (comment.editContenu.trim() === '') return;
                fetch(`/commentaires/${commentId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({ contenu: comment.editContenu, note: comment.editNote })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.commentaires[index].contenu = comment.editContenu;
                            this.commentaires[index].note = comment.editNote;
                            this.commentaires[index].editing = false;
                            this.updateAverageRating();
                        }
                    })
                    .catch(error => console.error('Erreur mise à jour commentaire:', error));
            },

            deleteComment(commentId) {
                if (!confirm('Êtes-vous sûr de vouloir supprimer ce commentaire ?')) return;
                fetch(`/commentaires/${commentId}`, {
                    method: 'DELETE',
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.commentaires = this.commentaires.filter(comment => comment.id !== commentId);
                            this.userHasCommented = false;
                            this.canComment = true;
                            this.totalCommentaires--;
                            this.updateAverageRating();
                        }
                    })
                    .catch(error => console.error('Erreur suppression commentaire:', error));
            },

            toggleVisibility(commentId, index) {
                fetch(`/commentaires/${commentId}/visibility`, {
                    method: 'PATCH',
                    headers: {
                        'Authorization': `Bearer ${this.getToken()}`,
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) this.commentaires[index].is_hidden = data.is_hidden;
                    })
                    .catch(error => console.error('Erreur changement visibilité:', error));
            },

            updateAverageRating() {
                if (this.commentaires.length > 0) {
                    const sum = this.commentaires.reduce((acc, comment) => acc + comment.note, 0);
                    this.noteMoyenne = (sum / this.commentaires.length).toFixed(1);
                } else {
                    this.noteMoyenne = 0;
                }
            },

            formatDate(dateString) {
                return new Date(dateString).toLocaleDateString('fr-FR', { day: 'numeric', month: 'long', year: 'numeric' });
            },

            getToken() { return localStorage.getItem('auth_token') || ''; }
        };
    }
</script>
@endpush