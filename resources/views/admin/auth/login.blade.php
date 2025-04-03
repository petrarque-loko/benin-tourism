<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Tourisme Bénin</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');
        
        body {
            font-family: 'Poppins', sans-serif;
            background-image: url('/images/background.jpg'); 
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
        }

        .glass-effect {
            background: rgba(255, 255, 255, 0.25);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.18);
        }

        .input-effect:focus {
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.5);
        }

        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-10px); }
            100% { transform: translateY(0px); }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center  ">
    <div class="max-w-md w-full bg-white rounded-xl shadow-2xl overflow-hidden transform transition-all hover:scale-105 duration-300">
        <!-- Header avec image emblématique du Bénin -->
        <div class="h-32 bg-gradient-to-r from-yellow-400 via-green-500 to-blue-500 flex items-center justify-center relative overflow-hidden">
    <div class="absolute inset-0 bg-black opacity-10"></div>
    <img src="/images/bg2.jpeg" alt="Tourisme Bénin" class="z-10 floating">
    <span class="absolute z-20 text-green-500 text-2xl font-bold">Bénin Tourisme</span>
</div>

              
        <!-- Titre avec icône -->
        <div class="px-4 py-5 sm:p-6 text-center">
            <div class="flex justify-center items-center space-x-2 mb-6">
                <i class="fas fa-user-shield text-2xl text-indigo-600"></i>
                <h2 class="text-2xl font-bold text-gray-800">Administration Tourisme Bénin</h2>
            </div>
            
            <!-- Alerte d'erreur -->
            <div class="mb-4 bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded hidden" id="errorAlert">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <i class="fas fa-exclamation-circle"></i>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm" id="errorMessage">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            
            <!-- Formulaire -->
            <form method="POST" action="{{ route('admin.login') }}" class="space-y-6">
                @csrf
                
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-envelope text-indigo-500 mr-2"></i>
                        Adresse e-mail
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                            class="input-effect w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                            placeholder="exemple@tourisme-benin.com">
                    </div>
                    <div class="text-red-500 text-xs mt-1 hidden" id="emailError"></div>
                </div>
                
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1 flex items-center">
                        <i class="fas fa-lock text-indigo-500 mr-2"></i>
                        Mot de passe
                    </label>
                    <div class="relative rounded-md shadow-sm">
                        <input type="password" id="password" name="password" required
                            class="input-effect w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none"
                            placeholder="Votre mot de passe">
                        <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-500">
                            <i class="fas fa-eye"></i>
                        </button>
                    </div>
                    <div class="text-red-500 text-xs mt-1 hidden" id="passwordError"></div>
                </div>
                
                <div class="flex items-center">
                    <input id="remember" name="remember" type="checkbox" 
                        class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                    <label for="remember" class="ml-2 block text-sm text-gray-700 cursor-pointer hover:text-indigo-500 transition-colors duration-200">
                        Se souvenir de moi
                    </label>
                </div>
                
                <div>
                    <button type="submit" 
                        class="w-full flex justify-center items-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-base font-medium text-white bg-gradient-to-r from-indigo-600 to-blue-500 hover:from-indigo-700 hover:to-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transform transition-all duration-200 hover:-translate-y-1">
                        <i class="fas fa-sign-in-alt mr-2"></i>
                        Connexion
                    </button>
                </div>
            </form>
            
            <!-- Bas de page -->
            <div class="mt-6 text-center">
                <p class="text-xs text-gray-500">
                    &copy; 2025 Tourisme Bénin. Tous droits réservés.
                </p>
                <div class="flex justify-center space-x-4 mt-3">
                    <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-200">
                        <i class="fab fa-facebook-f"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-200">
                        <i class="fab fa-twitter"></i>
                    </a>
                    <a href="#" class="text-gray-400 hover:text-indigo-500 transition-colors duration-200">
                        <i class="fab fa-instagram"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <script>
        // Afficher/masquer le mot de passe
        document.getElementById('togglePassword').addEventListener('click', function() {
            const passwordInput = document.getElementById('password');
            const icon = this.querySelector('i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        });
        
        // Animation au focus des champs
        const inputs = document.querySelectorAll('input:not([type="checkbox"])');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('scale-105');
            });
            
            input.addEventListener('blur', () => {
                input.parentElement.classList.remove('scale-105');
            });
        });
        
        // Gestion de l'affichage des erreurs (simulé pour la démo)
        const errorAlert = document.getElementById('errorAlert');
        const errorMessage = document.getElementById('errorMessage');
        
        // Décommenter pour simuler une erreur
        /*
        errorAlert.classList.remove('hidden');
        errorMessage.textContent = "Identifiants incorrects. Veuillez réessayer.";
        */
    </script>
</body>
</html>



