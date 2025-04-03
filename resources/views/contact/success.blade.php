<!-- resources/views/contact/success.blade.php -->
@extends('layouts.app')

@section('title', 'Message Envoyé | Tourisme Bénin')

@section('content')
<div class="bg-gradient-to-b from-blue-50 to-green-50 min-h-screen py-16"

style="background-image: url('/images/background.jpg');">


    <div class="container mx-auto px-6 text-center">
        <div 
            x-data="{ show: false }" 
            x-init="setTimeout(() => show = true, 300)"
            x-show="show"
            x-transition:enter="transition ease-out duration-700"
            x-transition:enter-start="opacity-0 transform scale-95"
            x-transition:enter-end="opacity-100 transform scale-100"
            class="max-w-2xl mx-auto bg-white rounded-xl shadow-xl overflow-hidden"
        >
            <div class="p-8 sm:p-12">
                <div class="w-16 h-16 bg-green-100 rounded-full mx-auto flex items-center justify-center">
                    <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                
                <h2 class="mt-6 text-2xl font-bold text-center text-gray-900">Message envoyé avec succès !</h2>
                <p class="mt-4 text-center text-gray-600">
                    Merci de nous avoir contactés. Notre équipe va étudier votre demande et vous répondra dans les plus brefs délais.
                </p>
                
                <div class="mt-8 flex justify-center">
                    <a 
                        href="{{ route('contact.index') }}" 
                        class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg transition-all duration-300 mx-2"
                    >
                        Retour
                    </a>
                    <a 
                        href="{{ route('index') }}" 
                        class="px-6 py-3 bg-teal-600 hover:bg-teal-700 text-white rounded-lg transition-all duration-300 mx-2"
                    >
                        Accueil
                    </a>
                </div>
            </div>
            
            <div class="px-8 py-4 bg-gray-50 border-t border-gray-100 text-center">
                <p class="text-sm text-gray-600">
                    Si vous avez besoin d'une réponse urgente, n'hésitez pas à nous appeler au <a href="tel:+22967682453" class="text-teal-600 hover:underline">+229 67 68 24 53</a>
                </p>
            </div>
        </div>
    </div>
</div>
@endsection