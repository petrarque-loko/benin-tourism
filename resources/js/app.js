// Import Alpine.js (si vous préférez ne pas utiliser CDN)
// import Alpine from 'alpinejs';
// window.Alpine = Alpine;
// Alpine.start();

// Votre code JavaScript personnalisé ici
document.addEventListener('DOMContentLoaded', function() {
    // Code d'initialisation
    console.log('Application initialisée');
});

// Fonctions d'aide pour le AJAX avec CSRF Token
window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// Récupérer le token CSRF depuis la meta tag
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
} else {
    console.error('CSRF token not found');
}