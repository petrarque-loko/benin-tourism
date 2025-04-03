<div 
    x-data="imageCarousel()" 
    x-init="init()"
    class="relative w-full h-96 overflow-hidden"
>
    <div 
        class="flex transition-transform duration-500 ease-in-out" 
        :style="`transform: translateX(-${currentIndex * 100}%)`"
    >
        @foreach($site->medias as $media)
        <div class="w-full flex-shrink-0">
            <img 
                src="{{ asset('storage/' . $media->url) }}" 
                alt="{{ $site->nom }} - Photo {{ $loop->iteration }}"
                class="w-full h-full object-cover"
                @click="openLightbox('{{ asset('storage/' . $media->url) }}')"
            >
        </div>
        @endforeach
    </div>

    <button 
        @click="prev()" 
        class="absolute top-1/2 left-4 transform -translate-y-1/2 bg-white/50 rounded-full p-2 hover:bg-white/75 transition"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
        </svg>
    </button>

    <button 
        @click="next()" 
        class="absolute top-1/2 right-4 transform -translate-y-1/2 bg-white/50 rounded-full p-2 hover:bg-white/75 transition"
    >
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
        </svg>
    </button>

    <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
        @foreach($site->medias as $index => $media)
        <button 
            @click="goToSlide({{ $index }})"
            class="w-2 h-2 rounded-full"
            :class="currentIndex === {{ $index }} ? 'bg-indigo-600' : 'bg-gray-300'"
        ></button>
        @endforeach
    </div>
</div>

<script>
function imageCarousel() {
    return {
        currentIndex: 0,
        totalSlides: {{ $site->medias->count() }},
        
        init() {
            // Auto-advance every 5 seconds
            setInterval(() => {
                this.next();
            }, 5000);
        },
        
        next() {
            this.currentIndex = (this.currentIndex + 1) % this.totalSlides;
        },
        
        prev() {
            this.currentIndex = (this.currentIndex - 1 + this.totalSlides) % this.totalSlides;
        },
        
        goToSlide(index) {
            this.currentIndex = index;
        },
        
        openLightbox(imageSrc) {
            const lightbox = document.getElementById('lightbox');
            const lightboxImage = document.getElementById('lightboxImage');
            
            lightboxImage.src = imageSrc;
            lightbox.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }
    }
}
</script>