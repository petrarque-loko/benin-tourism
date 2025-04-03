@extends('layouts.app')

@section('title', 'À Propos - Tourisme Bénin')

@section('styles')
<style>
    @keyframes float {
        0% { transform: translateY(0px); }
        50% { transform: translateY(-10px); }
        100% { transform: translateY(0px); }
    }
    
    .floating {
        animation: float 3s ease-in-out infinite;
    }
    
    .stagger-animate > * {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
    
    .stagger-animate.visible > *:nth-child(1) { 
        opacity: 1; 
        transform: translateY(0); 
        transition-delay: 0.1s; 
    }
    .stagger-animate.visible > *:nth-child(2) { 
        opacity: 1; 
        transform: translateY(0); 
        transition-delay: 0.2s; 
    }
    .stagger-animate.visible > *:nth-child(3) { 
        opacity: 1; 
        transform: translateY(0); 
        transition-delay: 0.3s; 
    }
    .stagger-animate.visible > *:nth-child(4) { 
        opacity: 1; 
        transform: translateY(0); 
        transition-delay: 0.4s; 
    }
</style>
@endsection

@section('content')
<div x-data="{
    activeTestimonial: 0,
    intersectionVisible: {},
    checkIntersection(el) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.intersectionVisible[el.id] = true;
                    observer.unobserve(el);
                }
            });
        }, { threshold: 0.3 });
        
        observer.observe(el);
    }
}">

    <!-- Hero Section -->
    <div class="relative overflow-hidden bg-gradient-to-r from-green-600 to-yellow-500"
     style="background-image: url('{{ asset('images/bg2.jpeg') }}'); background-size: cover; background-position: center;">

    
        <div class="absolute inset-0 bg-black opacity-30"></div>
        <div class="container mx-auto px-4 py-24 relative z-10">
            <div class="max-w-3xl mx-auto text-center">
                <h1 class="text-4xl md:text-5xl lg:text-6xl font-bold text-white mb-6 opacity-0 transform translate-y-10"
                    x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), 100)"
                    class="transition-all duration-1000 ease-out">
                    {{ $aboutData['title'] }}
                </h1>
                <p class="text-xl text-white leading-relaxed mb-8 opacity-0 transform translate-y-10"
                    x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), 300)"
                    class="transition-all duration-1000 ease-out">
                    {{ $aboutData['description'] }}
                </p>
                <div class="opacity-0 transform translate-y-10"
                    x-init="setTimeout(() => $el.classList.add('opacity-100', 'translate-y-0'), 500)"
                    class="transition-all duration-1000 ease-out">
                    <a href="#mission" class="inline-flex items-center bg-white text-green-700 font-semibold px-6 py-3 rounded-full hover:bg-green-50 transition duration-300 group">
                        <span>Découvrir notre mission</span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2 group-hover:translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>
        <div class="absolute bottom-0 left-0 w-full h-16 bg-white" style="clip-path: polygon(0 100%, 100% 100%, 100% 0);"></div>
    </div>

    <!-- Mission Section -->
    <section id="mission" class="py-20 bg-yellow-500">
        <div class="container mx-auto px-4">
            <div class="flex flex-col md:flex-row items-center justify-between gap-12">
                <div class="md:w-1/2">
                    <div x-init="checkIntersection($el)" id="mission-content" 
                        :class="{ 'visible': intersectionVisible['mission-content'] }"
                        class="stagger-animate">
                        <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-6">Notre Mission</h2>
                        <p class="text-lg text-gray-600 leading-relaxed mb-8">{{ $aboutData['mission'] }}</p>
                        <div class="flex flex-col sm:flex-row gap-4">
                                Explorez nos destinations
                            </a>
                                Contactez-nous
                            </a>
                        </div>
                    </div>
                </div>
                <div class="md:w-1/2">
                    <div class="relative">
                        <div class="absolute -top-4 -left-4 w-24 h-24 bg-yellow-400 rounded-full opacity-20 floating"></div>
                        <div class="absolute -bottom-4 -right-4 w-32 h-32 bg-green-400 rounded-full opacity-20 floating" style="animation-delay: 1s;"></div>
                        <img 
                            src="{{ asset('images/benin-culture.jpg') }}" 
                            alt="Culture béninoise" 
                            class="w-full h-auto rounded-2xl shadow-xl"
                            x-init="$el.classList.add('opacity-0', 'transform', 'scale-95'); 
                                    checkIntersection($el);"
                            :class="{ 'opacity-100 scale-100': intersectionVisible[$el.id] }"
                            class="transition-all duration-1000 ease-out"
                            id="mission-image"
                        />
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Statistics Section -->
    <section class="py-20 ">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6 md:gap-12"
                x-init="checkIntersection($el)" 
                id="stats-section" 
                :class="{ 'visible': intersectionVisible['stats-section'] }"
                class="stagger-animate">
                @foreach($aboutData['statistics'] as $stat)
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 mb-4">
                        <span class="text-green-600 text-2xl font-bold">{{ substr($stat['value'], 0, 1) }}</span>
                    </div>
                    <h3 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2"
                        x-data="{ 
                            start: 0, 
                            end: {{ intval($stat['value']) }},
                            duration: 2000,
                            suffix: '{{ str_contains($stat['value'], '+') ? '+' : '' }}',
                            current: 0,
                            get displayValue() {
                                return this.current + this.suffix;
                            }
                        }"
                        x-init="$watch('intersectionVisible[\'stats-section\']', value => {
                            if (value) {
                                const interval = (end - start) / (duration / 16);
                                const timer = setInterval(() => {
                                    current += interval;
                                    if (current >= end) {
                                        current = end;
                                        clearInterval(timer);
                                    }
                                }, 16);
                            }
                        })"
                        x-text="displayValue">
                        0
                    </h3>
                    <p class="text-lg text-gray-600">{{ $stat['label'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Team Section -->
    <section class="py-20 ">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16"
                x-init="checkIntersection($el)" 
                id="team-heading" 
                :class="{ 'visible': intersectionVisible['team-heading'] }"
                class="stagger-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Notre équipe passionnée</h2>
                <p class="text-lg text-gray-600 leading-relaxed">Découvrez les personnes dévouées qui travaillent chaque jour pour faire découvrir la beauté et l'authenticité du Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                @foreach($aboutData['team'] as $index => $member)
                <div class="bg-white rounded-xl shadow-lg overflow-hidden transform hover:-translate-y-2 transition duration-300"
                    x-init="setTimeout(() => {
                        checkIntersection($el);
                    }, {{ $index * 200 }})"
                    id="team-member-{{ $index }}"
                    :class="{ 'opacity-100': intersectionVisible['team-member-{{ $index }}'] }"
                    class="opacity-0 transition-opacity duration-1000">
                    <div class="relative overflow-hidden">
                        <img src="{{ asset($member['image']) }}" alt="{{ $member['name'] }}" class="w-full h-64 object-cover object-center transition duration-500 hover:scale-110">
                        <div class="absolute bottom-0 left-0 right-0 bg-gradient-to-t from-black to-transparent h-32 opacity-70"></div>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $member['name'] }}</h3>
                        <p class="text-green-600 font-medium mb-4">{{ $member['position'] }}</p>
                        <p class="text-gray-600">{{ $member['bio'] }}</p>
                        <div class="mt-6 flex space-x-3">
                            <a href="#" class="text-gray-500 hover:text-green-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-green-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                                </svg>
                            </a>
                            <a href="#" class="text-gray-500 hover:text-green-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M4.98 3.5c0 1.381-1.11 2.5-2.48 2.5s-2.48-1.119-2.48-2.5c0-1.38 1.11-2.5 2.48-2.5s2.48 1.12 2.48 2.5zm.02 4.5h-5v16h5v-16zm7.982 0h-4.968v16h4.969v-8.399c0-4.67 6.029-5.052 6.029 0v8.399h4.988v-10.131c0-7.88-8.922-7.593-11.018-3.714v-2.155z"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Values Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16"
                x-init="checkIntersection($el)" 
                id="values-heading" 
                :class="{ 'visible': intersectionVisible['values-heading'] }"
                class="stagger-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Nos Valeurs</h2>
                <p class="text-lg text-gray-600 leading-relaxed">Ces principes guident toutes nos actions et décisions pour vous offrir une expérience authentique du Bénin.</p>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                @foreach($aboutData['values'] as $index => $value)
                <div class="bg-white rounded-xl p-8 shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition duration-300"
                    x-init="setTimeout(() => {
                        checkIntersection($el);
                    }, {{ $index * 150 }})"
                    id="value-card-{{ $index }}"
                    :class="{ 'opacity-100 translate-y-0': intersectionVisible['value-card-{{ $index }}'] }"
                    class="opacity-0 translate-y-4 transition-all duration-700">
                    <div class="w-16 h-16 rounded-full bg-green-100 flex items-center justify-center mb-6 mx-auto">
                        @switch($index)
                            @case(0)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4" />
                                </svg>
                                @break
                            @case(1)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                @break
                            @case(2)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                </svg>
                                @break
                            @case(3)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z" />
                                </svg>
                                @break
                        @endswitch
                    </div>
                    <h3 class="text-xl font-bold text-center text-gray-800 mb-4">{{ $value['title'] }}</h3>
                    <p class="text-gray-600 text-center">{{ $value['description'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section class="py-20">
        <div class="container mx-auto px-4">
            <div class="max-w-3xl mx-auto text-center mb-16"
                x-init="checkIntersection($el)" 
                id="testimonials-heading" 
                :class="{ 'visible': intersectionVisible['testimonials-heading'] }"
                class="stagger-animate">
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">Ce que disent nos voyageurs</h2>
                <p class="text-lg text-gray-600 leading-relaxed">Découvrez les expériences vécues par ceux qui ont exploré le Bénin avec nous.</p>
            </div>
            
            <div class="relative max-w-4xl mx-auto"
                x-init="checkIntersection($el)" 
                id="testimonials-carousel" 
                :class="{ 'opacity-100': intersectionVisible['testimonials-carousel'] }"
                class="opacity-0 transition-opacity duration-1000">
                <div class="overflow-hidden">
                    <div class="flex transition-transform duration-500 ease-in-out" :style="`transform: translateX(-${activeTestimonial * 100}%)`">
                        @foreach($aboutData['testimonials'] as $testimonial)
                        <div class="w-full flex-shrink-0 p-4">
                            <div class="bg-green-50 rounded-2xl p-8 md:p-10 shadow-lg">
                                <div class="flex flex-col md:flex-row gap-6 items-center">
                                <img src="{{ asset($testimonial['image']) }}" 
     alt="{{ $testimonial['name'] }}" 
     class="w-24 h-24 md:w-32 md:h-32 rounded-full object-cover border-4 border-white shadow-md">
                                    <div>
                                        <div class="flex mb-4">
                                            @for($i = 0; $i < 5; $i++)
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                            

                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                            </svg>
                                            @endfor
                                        </div>
                                        <p class="text-gray-600 text-lg mb-6 italic">{{ $testimonial['text'] }}</p>
                                        <h3 class="text-xl font-bold text-gray-800">{{ $testimonial['name'] }}</h3>
                                        <p class="text-green-600">{{ $testimonial['country'] }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <!-- Navigation buttons -->
                <div class="flex justify-center mt-8 space-x-3">
                    @foreach($aboutData['testimonials'] as $index => $testimonial)
                    <button 
                        @click="activeTestimonial = {{ $index }}" 
                        class="w-3 h-3 rounded-full transition-colors duration-300" 
                        :class="activeTestimonial === {{ $index }} ? 'bg-green-600' : 'bg-gray-300'"
                    ></button>
                    @endforeach
                </div>
                
                <!-- Arrow navigation -->
                <button 
                    @click="activeTestimonial = (activeTestimonial - 1 + {{ count($aboutData['testimonials']) }}) % {{ count($aboutData['testimonials']) }}" 
                    class="absolute top-1/2 -left-4 md:-left-12 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                    </svg>
                </button>
                <button 
                    @click="activeTestimonial = (activeTestimonial + 1) % {{ count($aboutData['testimonials']) }}" 
                    class="absolute top-1/2 -right-4 md:-right-12 transform -translate-y-1/2 bg-white rounded-full p-2 shadow-lg hover:bg-gray-50 transition duration-300"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                    </svg>
                </button>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20 bg-green-700 text-white">
        <div class="container mx-auto px-4">
            <div class="max-w-4xl mx-auto text-center"
                x-init="checkIntersection($el)" 
                id="cta-section" 
                :class="{ 'visible': intersectionVisible['cta-section'] }"
                class="stagger-animate">
                <h2 class="text-3xl md:text-4xl font-bold mb-6">Prêt à découvrir les merveilles du Bénin ?</h2>
                <p class="text-xl opacity-90 mb-8">Rejoignez-nous pour une aventure inoubliable à la découverte des trésors cachés de ce pays fascinant.</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                        Voir nos circuits
                    </a>
                        Nous contacter
                    </a>
                </div>
            </div>
        </div>
    </section>

</div>
@endsection

@section('scripts')
<script>
    // Additional JavaScript can be added here if needed
</script>
@endsection