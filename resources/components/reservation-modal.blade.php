<div 
    x-data="reservationModal()"
    x-cloak
>
    <!-- Reservation Button -->
    <button 
        @click="open = true"
        class="w-full px-4 py-2 bg-indigo-600 text-white font-semibold rounded-md hover:bg-indigo-700 transition flex items-center justify-center"
    >
        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
        </svg>
        Réserver
    </button>

    <!-- Modal Overlay -->
    <div 
        x-show="open" 
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-black/50 z-50 flex items-center justify-center"
        @click.self="open = false"
    >
        <!-- Modal Content -->
        <div 
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
            class="bg-white rounded-lg shadow-xl max-w-lg w-full mx-4 p-6"
        >
            <h2 class="text-2xl font-bold mb-4 text-gray-900">Réservation pour {{ $site->nom }}</h2>
            
            <form 
                action="{{ route('reservations.store') }}" 
                method="POST"
                @submit.prevent="submitReservation"
            >
                @csrf
                <input type="hidden" name="site_id" value="{{ $site->id }}">
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Date de réservation</label>
                    <input 
                        type="date" 
                        name="date" 
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Nombre de personnes</label>
                    <input 
                        type="number" 
                        name="nombre_personnes" 
                        min="1" 
                        max="10"
                        required
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                    >
                </div>
                
                <div class="mb-4">
                    <label class="block text-gray-700 mb-2">Notes supplémentaires (optionnel)</label>
                    <textarea 
                        name="notes"
                        rows="3"
                        class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                        placeholder="Informations complémentaires..."
                    ></textarea>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button 
                        type="button" 
                        @click="open = false"
                        class="px-4 py-2 bg-gray-100 text-gray-700 rounded-md hover:bg-gray-200 transition"
                    >
                        Annuler
                    </button>
                    <button 
                        type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition"
                    >
                        Confirmer
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function reservationModal() {
    return {
        open: false,
        submitReservation(event) {
            const form = event.target;
            
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Réservation confirmée !');
                    this.open = false;
                } else {
                    alert('Erreur lors de la réservation : ' + data.message);
                }
            })
            .catch(error => {
                console.error('Erreur:', error);
                alert('Une erreur est survenue.');
            });
        }
    }
}
</script>