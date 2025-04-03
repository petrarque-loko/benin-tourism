@extends('layouts.app')

@section('content')
<div class="bg-cover bg-center bg-fixed min-h-screen py-6" 
     style="background-image: url('/images/background.jpg');">
    <div class="container px-4 mx-auto">
        <div class="max-w-4xl mx-auto">
            <!-- Card avec effet de verre -->
            <div class="bg-white/80 backdrop-blur-lg rounded-2xl shadow-xl overflow-hidden border border-gray-100">
                <!-- En-tête avec style -->
                <div class="bg-gradient-to-r from-violet-600 to-indigo-600 p-6 md:p-8">
                    <h1 class="text-2xl md:text-3xl font-bold text-white flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                        </svg>
                        {{ __('Inscription') }}
                    </h1>
                    <p class="text-indigo-100 mt-2 max-w-xl">Rejoignez notre communauté et profitez de tous nos services exclusifs</p>
                </div>

                <!-- Corps du formulaire avec étapes -->
                <div class="p-6 md:p-8" x-data="{
                    currentStep: 1,
                    totalSteps: 4,
                    selectedRole: '',
                    showDocuments: false,
                    countryCode: '',
                    phoneNumber: '',
                    passwordVisible: false,
                    confirmPasswordVisible: false,
                    personalValid: false,
                    contactValid: false,
                    securityValid: false,
                    finalValid: false,
                    
                    init() {
                        this.$watch('selectedRole', (value) => {
                            const roleText = this.$el.querySelector(`option[value='${value}']`)?.textContent || '';
                            this.showDocuments = roleText === 'Guide Touristique' || roleText === 'Propriétaire Hébergement';
                        });
                        
                        // Vérifier la validité des champs lorsqu'ils changent
                        this.validateStep(1);
                    },
                    
                    getFullPhone() {
                        if (this.countryCode && this.phoneNumber) {
                            const prefix = this.countryCode.split('|')[0];
                            return prefix + ' ' + this.phoneNumber.replace(/^\+/, '');
                        }
                        return '';
                    },
                    
                    nextStep() {
                        if (this.validateStep(this.currentStep)) {
                            if (this.currentStep < this.totalSteps) {
                                this.currentStep++;
                                window.scrollTo(0, 0);
                            }
                        }
                    },
                    
                    prevStep() {
                        if (this.currentStep > 1) {
                            this.currentStep--;
                            window.scrollTo(0, 0);
                        }
                    },
                    
                    validateStep(step) {
                        if (step === 1) {
                            const nom = document.getElementById('nom').value;
                            const prenom = document.getElementById('prenom').value;
                            this.personalValid = nom.trim() !== '' && prenom.trim() !== '';
                            return this.personalValid;
                        } else if (step === 2) {
                            const email = document.getElementById('email').value;
                            const adresse = document.getElementById('adresse').value;
                            const countryCode = this.countryCode;
                            const phoneNumber = this.phoneNumber;
                            
                            const emailValid = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
                            this.contactValid = emailValid && adresse.trim() !== '' && countryCode !== '' && phoneNumber.trim() !== '';
                            return this.contactValid;
                        } else if (step === 3) {
                            const password = document.getElementById('password').value;
                            const passwordConfirm = document.getElementById('password-confirm').value;
                            
                            const pwdValid = password.length >= 8;
                            const pwdMatch = password === passwordConfirm;
                            
                            this.securityValid = pwdValid && pwdMatch;
                            return this.securityValid;
                        } else if (step === 4) {
                            const role = this.selectedRole;
                            const terms = document.getElementById('terms').checked;
                            
                            let docsValid = true;
                            if (this.showDocuments) {
                                const docs = document.querySelectorAll('input[name=\'documents[]\']');
                                for (let i = 0; i < docs.length; i++) {
                                    if (docs[i].files.length === 0) {
                                        docsValid = false;
                                        break;
                                    }
                                }
                            }
                            
                            this.finalValid = role !== '' && terms && docsValid;
                            return this.finalValid;
                        }
                        
                        return true;
                    }
                }">
                    <!-- Indicateur de progression -->
                    <div class="mb-8">
                        <div class="relative">
                            <div class="overflow-hidden h-2 mb-4 flex rounded-full bg-gray-200">
                                <div class="shadow-none flex flex-col justify-center bg-indigo-500 rounded-full"
                                    :style="'width: ' + (currentStep / totalSteps * 100) + '%'"></div>
                            </div>
                            
                            <!-- Étapes numérotées -->
                            <div class="flex justify-between items-center">
                                <template x-for="step in totalSteps" :key="step">
                                    <div class="relative">
                                        <div :class="step <= currentStep ? 'bg-indigo-600 text-white border-indigo-600' : 'bg-white text-gray-600 border-gray-300'"
                                            class="w-8 h-8 flex items-center justify-center rounded-full border transition-colors duration-300 text-sm font-semibold">
                                            <span x-text="step"></span>
                                        </div>
                                        <span class="absolute mt-2 text-xs -ml-4 whitespace-nowrap font-medium" :class="step <= currentStep ? 'text-indigo-600' : 'text-gray-500'">
                                            <span x-show="step == 1">Identité</span>
                                            <span x-show="step == 2">Contact</span>
                                            <span x-show="step == 3">Sécurité</span>
                                            <span x-show="step == 4">Finalisation</span>
                                        </span>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>
                    
                    <form method="POST" action="{{ route('register') }}" enctype="multipart/form-data" class="space-y-6" @submit="$refs.fullPhone.value = getFullPhone()">
                        @csrf
                        <input type="hidden" name="telephone_complet" x-ref="fullPhone">

                        <!-- Étape 1: Identité -->
                        <div x-show="currentStep === 1" x-transition>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                Informations personnelles
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Nom -->
                                <div class="space-y-2">
                                    <label for="nom" class="block text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('Nom') }} <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="nom" type="text" 
                                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('nom') border-red-500 @enderror" 
                                            name="nom" value="{{ old('nom') }}" required autocomplete="nom" autofocus 
                                            placeholder="Votre nom"
                                            @input="validateStep(1)">
                                        @error('nom')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Prénom -->
                                <div class="space-y-2">
                                    <label for="prenom" class="block text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('Prénom') }} <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input id="prenom" type="text" 
                                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('prenom') border-red-500 @enderror" 
                                            name="prenom" value="{{ old('prenom') }}" required autocomplete="prenom"
                                            placeholder="Votre prénom"
                                            @input="validateStep(1)">
                                        @error('prenom')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-end">
                                <button type="button" 
                                    class="py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 flex items-center"
                                    :class="{'opacity-50 cursor-not-allowed': !personalValid, 'hover:bg-indigo-700': personalValid}"
                                    :disabled="!personalValid"
                                    @click="nextStep()">
                                    Suivant
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <!-- Étape 2: Contact -->
                        <div x-show="currentStep === 2" x-transition>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                Informations de contact
                            </h3>
                            
                            <!-- Email -->
                            <div class="space-y-2 mb-6">
                                <label for="email" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                    </svg>
                                    {{ __('Adresse email') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <input id="email" type="email" 
                                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('email') border-red-500 @enderror" 
                                        name="email" value="{{ old('email') }}" required autocomplete="email"
                                        placeholder="votre@email.com"
                                        @input="validateStep(2)">
                                    @error('email')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Téléphone -->
                            <div class="space-y-2 mb-6">
                                <label for="country_code" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                    </svg>
                                    {{ __('Téléphone') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="flex flex-col md:flex-row space-y-3 md:space-y-0 md:space-x-3">
                                    <select id="country_code" x-model="countryCode"
                                        class="px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 md:w-1/3 @error('country_code') border-red-500 @enderror" 
                                        name="country_code" required
                                        @change="validateStep(2)">
                                        <option value="">Sélectionnez un pays</option>
                                        <option value="+93|Afghanistan">🇦🇫 Afghanistan (+93)</option>
                                        <option value="+355|Albanie">🇦🇱 Albanie (+355)</option>
                                        <option value="+213|Algérie">🇩🇿 Algérie (+213)</option>
                                        <option value="+376|Andorre">🇦🇩 Andorre (+376)</option>
                                        <option value="+244|Angola">🇦🇴 Angola (+244)</option>
                                        <option value="+1|Antigua-et-Barbuda">🇦🇬 Antigua-et-Barbuda (+1)</option>
                                        <option value="+966|Arabie Saoudite">🇸🇦 Arabie Saoudite (+966)</option>
                                        <option value="+54|Argentine">🇦🇷 Argentine (+54)</option>
                                        <option value="+374|Arménie">🇦🇲 Arménie (+374)</option>
                                        <option value="+61|Australie">🇦🇺 Australie (+61)</option>
                                        <option value="+43|Autriche">🇦🇹 Autriche (+43)</option>
                                        <option value="+994|Azerbaïdjan">🇦🇿 Azerbaïdjan (+994)</option>
                                        <option value="+1|Bahamas">🇧🇸 Bahamas (+1)</option>
                                        <option value="+973|Bahreïn">🇧🇭 Bahreïn (+973)</option>
                                        <option value="+880|Bangladesh">🇧🇩 Bangladesh (+880)</option>
                                        <option value="+1|Barbade">🇧🇧 Barbade (+1)</option>
                                        <option value="+375|Biélorussie">🇧🇾 Biélorussie (+375)</option>
                                        <option value="+32|Belgique">🇧🇪 Belgique (+32)</option>
                                        <option value="+501|Belize">🇧🇿 Belize (+501)</option>
                                        <option value="+229|Bénin">🇧🇯 Bénin (+229)</option>
                                        <option value="+975|Bhoutan">🇧🇹 Bhoutan (+975)</option>
                                        <option value="+591|Bolivie">🇧🇴 Bolivie (+591)</option>
                                        <option value="+387|Bosnie-Herzégovine">🇧🇦 Bosnie-Herzégovine (+387)</option>
                                        <option value="+267|Botswana">🇧🇼 Botswana (+267)</option>
                                        <option value="+55|Brésil">🇧🇷 Brésil (+55)</option>
                                        <option value="+673|Brunei">🇧🇳 Brunei (+673)</option>
                                        <option value="+359|Bulgarie">🇧🇬 Bulgarie (+359)</option>
                                        <option value="+226|Burkina Faso">🇧🇫 Burkina Faso (+226)</option>
                                        <option value="+257|Burundi">🇧🇮 Burundi (+257)</option>
                                        <option value="+855|Cambodge">🇰🇭 Cambodge (+855)</option>
                                        <option value="+237|Cameroun">🇨🇲 Cameroun (+237)</option>
                                        <option value="+1|Canada">🇨🇦 Canada (+1)</option>
                                        <option value="+238|Cap-Vert">🇨🇻 Cap-Vert (+238)</option>
                                        <option value="+236|République centrafricaine">🇨🇫 République centrafricaine (+236)</option>
                                        <option value="+56|Chili">🇨🇱 Chili (+56)</option>
                                        <option value="+86|Chine">🇨🇳 Chine (+86)</option>
                                        <option value="+357|Chypre">🇨🇾 Chypre (+357)</option>
                                        <option value="+57|Colombie">🇨🇴 Colombie (+57)</option>
                                        <option value="+269|Comores">🇰🇲 Comores (+269)</option>
                                        <option value="+242|République du Congo">🇨🇬 République du Congo (+242)</option>
                                        <option value="+243|République démocratique du Congo">🇨🇩 République démocratique du Congo (+243)</option>
                                        <option value="+82|Corée du Sud">🇰🇷 Corée du Sud (+82)</option>
                                        <option value="+850|Corée du Nord">🇰🇵 Corée du Nord (+850)</option>
                                        <option value="+506|Costa Rica">🇨🇷 Costa Rica (+506)</option>
                                        <option value="+225|Côte d'Ivoire">🇨🇮 Côte d'Ivoire (+225)</option>
                                        <option value="+385|Croatie">🇭🇷 Croatie (+385)</option>
                                        <option value="+53|Cuba">🇨🇺 Cuba (+53)</option>
                                        <option value="+45|Danemark">🇩🇰 Danemark (+45)</option>
                                        <option value="+253|Djibouti">🇩🇯 Djibouti (+253)</option>
                                        <option value="+1|République dominicaine">🇩🇴 République dominicaine (+1)</option>
                                        <option value="+1|Dominique">🇩🇲 Dominique (+1)</option>
                                        <option value="+20|Égypte">🇪🇬 Égypte (+20)</option>
                                        <option value="+971|Émirats arabes unis">🇦🇪 Émirats arabes unis (+971)</option>
                                        <option value="+593|Équateur">🇪🇨 Équateur (+593)</option>
                                        <option value="+291|Érythrée">🇪🇷 Érythrée (+291)</option>
                                        <option value="+34|Espagne">🇪🇸 Espagne (+34)</option>
                                        <option value="+372|Estonie">🇪🇪 Estonie (+372)</option>
                                        <option value="+268|Eswatini">🇸🇿 Eswatini (+268)</option>
                                        <option value="+1|États-Unis">🇺🇸 États-Unis (+1)</option>
                                        <option value="+251|Éthiopie">🇪🇹 Éthiopie (+251)</option>
                                        <option value="+679|Fidji">🇫🇯 Fidji (+679)</option>
                                        <option value="+358|Finlande">🇫🇮 Finlande (+358)</option>
                                        <option value="+33|France">🇫🇷 France (+33)</option>
                                        <option value="+241|Gabon">🇬🇦 Gabon (+241)</option>
                                        <option value="+220|Gambie">🇬🇲 Gambie (+220)</option>
                                        <option value="+995|Géorgie">🇬🇪 Géorgie (+995)</option>
                                        <option value="+233|Ghana">🇬🇭 Ghana (+233)</option>
                                        <option value="+30|Grèce">🇬🇷 Grèce (+30)</option>
                                        <option value="+1|Grenade">🇬🇩 Grenade (+1)</option>
                                        <option value="+502|Guatemala">🇬🇹 Guatemala (+502)</option>
                                        <option value="+224|Guinée">🇬🇳 Guinée (+224)</option>
                                        <option value="+245|Guinée-Bissau">🇬🇼 Guinée-Bissau (+245)</option>
                                        <option value="+240|Guinée équatoriale">🇬🇶 Guinée équatoriale (+240)</option>
                                        <option value="+592|Guyana">🇬🇾 Guyana (+592)</option>
                                        <option value="+509|Haïti">🇭🇹 Haïti (+509)</option>
                                        <option value="+504|Honduras">🇭🇳 Honduras (+504)</option>
                                        <option value="+36|Hongrie">🇭🇺 Hongrie (+36)</option>
                                        <option value="+91|Inde">🇮🇳 Inde (+91)</option>
                                        <option value="+62|Indonésie">🇮🇩 Indonésie (+62)</option>
                                        <option value="+964|Irak">🇮🇶 Irak (+964)</option>
                                        <option value="+98|Iran">🇮🇷 Iran (+98)</option>
                                        <option value="+353|Irlande">🇮🇪 Irlande (+353)</option>
                                        <option value="+354|Islande">🇮🇸 Islande (+354)</option>
                                        <option value="+972|Israël">🇮🇱 Israël (+972)</option>
                                        <option value="+39|Italie">🇮🇹 Italie (+39)</option>
                                        <option value="+1|Jamaïque">🇯🇲 Jamaïque (+1)</option>
                                        <option value="+81|Japon">🇯🇵 Japon (+81)</option>
                                        <option value="+962|Jordanie">🇯🇴 Jordanie (+962)</option>
                                        <option value="+7|Kazakhstan">🇰🇿 Kazakhstan (+7)</option>
                                        <option value="+254|Kenya">🇰🇪 Kenya (+254)</option>
                                        <option value="+996|Kirghizistan">🇰🇬 Kirghizistan (+996)</option>
                                        <option value="+686|Kiribati">🇰🇮 Kiribati (+686)</option>
                                        <option value="+965|Koweït">🇰🇼 Koweït (+965)</option>
                                        <option value="+856|Laos">🇱🇦 Laos (+856)</option>
                                        <option value="+266|Lesotho">🇱🇸 Lesotho (+266)</option>
                                        <option value="+371|Lettonie">🇱🇻 Lettonie (+371)</option>
                                        <option value="+961|Liban">🇱🇧 Liban (+961)</option>
                                        <option value="+231|Liberia">🇱🇷 Liberia (+231)</option>
                                        <option value="+218|Libye">🇱🇾 Libye (+218)</option>
                                        <option value="+423|Liechtenstein">🇱🇮 Liechtenstein (+423)</option>
                                        <option value="+370|Lituanie">🇱🇹 Lituanie (+370)</option>
                                        <option value="+352|Luxembourg">🇱🇺 Luxembourg (+352)</option>
                                        <option value="+389|Macédoine du Nord">🇲🇰 Macédoine du Nord (+389)</option>
                                        <option value="+261|Madagascar">🇲🇬 Madagascar (+261)</option>
                                        <option value="+60|Malaisie">🇲🇾 Malaisie (+60)</option>
                                        <option value="+265|Malawi">🇲🇼 Malawi (+265)</option>
                                        <option value="+960|Maldives">🇲🇻 Maldives (+960)</option>
                                        <option value="+223|Mali">🇲🇱 Mali (+223)</option>
                                        <option value="+356|Malte">🇲🇹 Malte (+356)</option>
                                        <option value="+212|Maroc">🇲🇦 Maroc (+212)</option>
                                        <option value="+692|Îles Marshall">🇲🇭 Îles Marshall (+692)</option>
                                        <option value="+230|Maurice">🇲🇺 Maurice (+230)</option>
                                        <option value="+222|Mauritanie">🇲🇷 Mauritanie (+222)</option>
                                        <option value="+52|Mexique">🇲🇽 Mexique (+52)</option>
                                        <option value="+691|Micronésie">🇫🇲 Micronésie (+691)</option>
                                        <option value="+373|Moldavie">🇲🇩 Moldavie (+373)</option>
                                        <option value="+377|Monaco">🇲🇨 Monaco (+377)</option>
                                        <option value="+976|Mongolie">🇲🇳 Mongolie (+976)</option>
                                        <option value="+382|Monténégro">🇲🇪 Monténégro (+382)</option>
                                        <option value="+258|Mozambique">🇲🇿 Mozambique (+258)</option>
                                        <option value="+95|Myanmar">🇲🇲 Myanmar (+95)</option>
                                        <option value="+264|Namibie">🇳🇦 Namibie (+264)</option>
                                        <option value="+674|Nauru">🇳🇷 Nauru (+674)</option>
                                        <option value="+977|Népal">🇳🇵 Népal (+977)</option>
                                        <option value="+505|Nicaragua">🇳🇮 Nicaragua (+505)</option>
                                        <option value="+227|Niger">🇳🇪 Niger (+227)</option>
                                        <option value="+234|Nigeria">🇳🇬 Nigeria (+234)</option>
                                        <option value="+47|Norvège">🇳🇴 Norvège (+47)</option>
                                        <option value="+64|Nouvelle-Zélande">🇳🇿 Nouvelle-Zélande (+64)</option>
                                        <option value="+968|Oman">🇴🇲 Oman (+968)</option>
                                        <option value="+256|Ouganda">🇺🇬 Ouganda (+256)</option>
                                        <option value="+998|Ouzbékistan">🇺🇿 Ouzbékistan (+998)</option>
                                        <option value="+92|Pakistan">🇵🇰 Pakistan (+92)</option>
                                        <option value="+680|Palaos">🇵🇼 Palaos (+680)</option>
                                        <option value="+970|Palestine">🇵🇸 Palestine (+970)</option>
                                        <option value="+507|Panama">🇵🇦 Panama (+507)</option>
                                        <option value="+675|Papouasie-Nouvelle-Guinée">🇵🇬 Papouasie-Nouvelle-Guinée (+675)</option>
                                        <option value="+595|Paraguay">🇵🇾 Paraguay (+595)</option>
                                        <option value="+31|Pays-Bas">🇳🇱 Pays-Bas (+31)</option>
                                        <option value="+51|Pérou">🇵🇪 Pérou (+51)</option>
                                        <option value="+63|Philippines">🇵🇭 Philippines (+63)</option>
                                        <option value="+48|Pologne">🇵🇱 Pologne (+48)</option>
                                        <option value="+351|Portugal">🇵🇹 Portugal (+351)</option>
                                        <option value="+974|Qatar">🇶🇦 Qatar (+974)</option>
                                        <option value="+40|Roumanie">🇷🇴 Roumanie (+40)</option>
                                        <option value="+44|Royaume-Uni">🇬🇧 Royaume-Uni (+44)</option>
                                        <option value="+7|Russie">🇷🇺 Russie (+7)</option>
                                        <option value="+250|Rwanda">🇷🇼 Rwanda (+250)</option>
                                        <option value="+1|Saint-Kitts-et-Nevis">🇰🇳 Saint-Kitts-et-Nevis (+1)</option>
                                        <option value="+1|Sainte-Lucie">🇱🇨 Sainte-Lucie (+1)</option>
                                        <option value="+1|Saint-Vincent-et-les-Grenadines">🇻🇨 Saint-Vincent-et-les-Grenadines (+1)</option>
                                        <option value="+685|Samoa">🇼🇸 Samoa (+685)</option>
                                        <option value="+378|Saint-Marin">🇸🇲 Saint-Marin (+378)</option>
                                        <option value="+239|São Tomé-et-Principe">🇸🇹 São Tomé-et-Principe (+239)</option>
                                        <option value="+221|Sénégal">🇸🇳 Sénégal (+221)</option>
                                        <option value="+381|Serbie">🇷🇸 Serbie (+381)</option>
                                        <option value="+248|Seychelles">🇸🇨 Seychelles (+248)</option>
                                        <option value="+232|Sierra Leone">🇸🇱 Sierra Leone (+232)</option>
                                        <option value="+65|Singapour">🇸🇬 Singapour (+65)</option>
                                        <option value="+421|Slovaquie">🇸🇰 Slovaquie (+421)</option>
                                        <option value="+386|Slovénie">🇸🇮 Slovénie (+386)</option>
                                        <option value="+252|Somalie">🇸🇴 Somalie (+252)</option>
                                        <option value="+249|Soudan">🇸🇩 Soudan (+249)</option>
                                        <option value="+211|Soudan du Sud">🇸🇸 Soudan du Sud (+211)</option>
                                        <option value="+94|Sri Lanka">🇱🇰 Sri Lanka (+94)</option>
                                        <option value="+46|Suède">🇸🇪 Suède (+46)</option>
                                        <option value="+41|Suisse">🇨🇭 Suisse (+41)</option>
                                        <option value="+597|Suriname">🇸🇷 Suriname (+597)</option>
                                        <option value="+963|Syrie">🇸🇾 Syrie (+963)</option>
                                        <option value="+992|Tadjikistan">🇹🇯 Tadjikistan (+992)</option>
                                        <option value="+255|Tanzanie">🇹🇿 Tanzanie (+255)</option>
                                        <option value="+235|Tchad">🇹🇩 Tchad (+235)</option>
                                        <option value="+420|République tchèque">🇨🇿 République tchèque (+420)</option>
                                        <option value="+66|Thaïlande">🇹🇭 Thaïlande (+66)</option>
                                        <option value="+670|Timor oriental">🇹🇱 Timor oriental (+670)</option>
                                        <option value="+228|Togo">🇹🇬 Togo (+228)</option>
                                        <option value="+676|Tonga">🇹🇴 Tonga (+676)</option>
                                        <option value="+1|Trinité-et-Tobago">🇹🇹 Trinité-et-Tobago (+1)</option>
                                        <option value="+216|Tunisie">🇹🇳 Tunisie (+216)</option>
                                        <option value="+993|Turkménistan">🇹🇲 Turkménistan (+993)</option>
                                        <option value="+90|Turquie">🇹🇷 Turquie (+90)</option>
                                        <option value="+688|Tuvalu">🇹🇻 Tuvalu (+688)</option>
                                        <option value="+380|Ukraine">🇺🇦 Ukraine (+380)</option>
                                        <option value="+598|Uruguay">🇺🇾 Uruguay (+598)</option>
                                        <option value="+678|Vanuatu">🇻🇺 Vanuatu (+678)</option>
                                        <option value="+379|Vatican">🇻🇦 Vatican (+379)</option>
                                        <option value="+58|Venezuela">🇻🇪 Venezuela (+58)</option>
                                        <option value="+84|Viêt Nam">🇻🇳 Viêt Nam (+84)</option>
                                        <option value="+967|Yémen">🇾🇪 Yémen (+967)</option>
                                        <option value="+260|Zambie">🇿🇲 Zambie (+260)</option>
                                        <option value="+263|Zimbabwe">🇿🇼 Zimbabwe (+263)</option>
                                    </select>
                                    <div class="relative flex-1">
                                        <input id="telephone" type="text" x-model="phoneNumber"
                                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('telephone') border-red-500 @enderror" 
                                            name="telephone" value="{{ old('telephone') }}" required 
                                            placeholder="Numéro sans le préfixe"
                                            @input="validateStep(2)">
                                        @error('telephone')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                                <p class="text-xs text-gray-500 mt-1 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Sélectionnez d'abord votre pays pour définir le préfixe téléphonique
                                </p>
                            </div>

                            <!-- Adresse -->
                            <div class="space-y-2 mb-6">
                                <label for="adresse" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ __('Adresse') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <textarea id="adresse" 
                                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('adresse') border-red-500 @enderror" 
                                        name="adresse" required placeholder="Votre adresse complète" rows="3"
                                        @input="validateStep(2)">{{ old('adresse') }}</textarea>
                                    @error('adresse')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-between">
                                <button type="button" 
                                    class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-200 flex items-center"
                                    @click="prevStep()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Précédent
                                </button>
                                
                                <button type="button" 
                                    class="py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 flex items-center"
                                    :class="{'opacity-50 cursor-not-allowed': !contactValid, 'hover:bg-indigo-700': contactValid}"
                                    :disabled="!contactValid"
                                    @click="nextStep()">
                                    Suivant
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Étape 3: Sécurité -->
                        <div x-show="currentStep === 3" x-transition>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                                Sécurité du compte
                            </h3>
                            
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                                <!-- Mot de passe -->
                                <div class="space-y-2">
                                    <label for="password" class="block text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                        </svg>
                                        {{ __('Mot de passe') }} <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input :type="passwordVisible ? 'text' : 'password'" id="password" 
                                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-10 transition duration-200 @error('password') border-red-500 @enderror" 
                                            name="password" required autocomplete="new-password"
                                            placeholder="Minimum 8 caractères"
                                            @input="validateStep(3)">
                                        <button type="button" @click="passwordVisible = !passwordVisible" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-500 focus:outline-none">
                                            <svg x-show="!passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                        @error('password')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">Le mot de passe doit contenir au moins 8 caractères</p>
                                </div>

                                <!-- Confirmation mot de passe -->
                                <div class="space-y-2">
                                    <label for="password-confirm" class="block text-sm font-medium text-gray-700 flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                        </svg>
                                        {{ __('Confirmation du mot de passe') }} <span class="text-red-500 ml-1">*</span>
                                    </label>
                                    <div class="relative">
                                        <input :type="confirmPasswordVisible ? 'text' : 'password'" id="password-confirm" 
                                            class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 pr-10 transition duration-200" 
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirmez votre mot de passe"
                                            @input="validateStep(3)">
                                        <button type="button" @click="confirmPasswordVisible = !confirmPasswordVisible" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-indigo-500 focus:outline-none">
                                            <svg x-show="!confirmPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            <svg x-show="confirmPasswordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mt-8 flex justify-between">
                                <button type="button" 
                                    class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-200 flex items-center"
                                    @click="prevStep()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Précédent
                                </button>
                                
                                <button type="button" 
                                    class="py-3 px-6 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200 flex items-center"
                                    :class="{'opacity-50 cursor-not-allowed': !securityValid, 'hover:bg-indigo-700': securityValid}"
                                    :disabled="!securityValid"
                                    @click="nextStep()">
                                    Suivant
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Étape 4: Finalisation -->
                        <div x-show="currentStep === 4" x-transition>
                            <h3 class="text-xl font-semibold text-gray-800 mb-6 flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                                Finalisation de l'inscription
                            </h3>

                            <!-- Type de compte -->
                            <div class="space-y-2 mb-6">
                                <label for="role_id" class="block text-sm font-medium text-gray-700 flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-2 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2" />
                                    </svg>
                                    {{ __('Type de compte') }} <span class="text-red-500 ml-1">*</span>
                                </label>
                                <div class="relative">
                                    <select id="role_id" x-model="selectedRole"
                                        class="block w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 transition duration-200 @error('role_id') border-red-500 @enderror" 
                                        name="role_id" required
                                        @change="validateStep(4)">
                                        <option value="">Sélectionnez un type de compte</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('role_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Documents section conditionnelle -->
                            <div x-show="showDocuments" x-transition:enter="transition ease-out duration-300" 
                                x-transition:enter-start="opacity-0 transform -translate-y-4" 
                                x-transition:enter-end="opacity-100 transform translate-y-0"
                                class="bg-blue-50 p-6 rounded-xl border border-blue-100 space-y-4 mb-6">
                                <div class="flex items-start space-x-3">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-blue-500 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <h3 class="text-lg font-semibold text-blue-800">Documents requis</h3>
                                        <p class="text-sm text-blue-600">Pour les guides touristiques et propriétaires d'hébergement, merci de fournir les documents nécessaires pour vérification.</p>
                                    </div>
                                </div>

                                <div id="documents-container" x-data="{ 
                                    documents: [{ type: 'Carte d\'identité', file: null }],
                                    addDocument() {
                                        this.documents.push({ type: 'Carte d\'identité', file: null });
                                    },
                                    removeDocument(index) {
                                        this.documents.splice(index, 1);
                                    },
                                    updateValidation() {
                                        $dispatch('input'); // Déclenche la validation
                                    }
                                }">
                                    <template x-for="(doc, index) in documents" :key="index">
                                        <div class="document-entry flex flex-col md:flex-row items-center gap-3 bg-white p-4 rounded-lg shadow-sm mb-3 relative">
                                            <select x-model="doc.type" :name="`document_types[]`" 
                                                class="w-full md:w-1/3 px-3 py-2 border border-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                                                @change="updateValidation()">
                                                <option value="Carte d'identité">Carte d'identité</option>
                                                <option value="Passeport">Passeport</option>
                                                <option value="Licence de guide">Licence de guide</option>
                                                <option value="Preuve de propriété">Preuve de propriété</option>
                                                <option value="Autre">Autre document</option>
                                            </select>
                                            
                                            <div class="w-full md:w-2/3 flex items-center">
                                                <div class="relative flex-1 group">
                                                    <input type="file" :name="`documents[]`" 
                                                        class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" 
                                                        required
                                                        @change="validateStep(4); updateValidation()">
                                                    <div class="bg-gray-50 border border-gray-300 border-dashed rounded-lg py-3 px-4 text-center hover:bg-gray-100 transition duration-200">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="mx-auto h-6 w-6 text-gray-400 group-hover:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12" />
                                                        </svg>
                                                        <p class="mt-1 text-xs text-gray-500 group-hover:text-gray-600">Cliquez ou glissez un fichier</p>
                                                    </div>
                                                </div>
                                                
                                                <button type="button" x-show="index === 0" 
                                                    @click="addDocument()" 
                                                    class="ml-2 p-2 bg-indigo-100 hover:bg-indigo-200 text-indigo-700 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                                                    </svg>
                                                </button>
                                                
                                                <button type="button" x-show="index > 0" 
                                                    @click="removeDocument(index)" 
                                                    class="ml-2 p-2 bg-red-100 hover:bg-red-200 text-red-700 rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition duration-200">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </template>
                                    
                                    <p class="text-xs text-blue-600 mt-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 inline mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        Formats acceptés: PDF, JPG, PNG. Taille maximum: 5MB
                                    </p>
                                </div>
                            </div>

                            <!-- CGU et Politique de confidentialité -->
                            <div class="space-y-2 mb-6">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="terms" type="checkbox" 
                                            class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded transition duration-200" 
                                            name="terms" required
                                            @change="validateStep(4)">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="terms" class="font-medium text-gray-700">
                                            {{ __('J\'accepte les conditions générales d\'utilisation') }} <span class="text-red-500">*</span>
                                        </label>
                                        <p class="text-gray-500 mt-1">
                                            En créant un compte, vous acceptez nos <a href="#" class="text-indigo-600 hover:text-indigo-800">Conditions Générales d'Utilisation</a> et notre <a href="#" class="text-indigo-600 hover:text-indigo-800">Politique de Confidentialité</a>.
                                        </p>
                                    </div>
                                </div>
                                @error('terms')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="mt-8 flex justify-between">
                                <button type="button" 
                                    class="py-3 px-6 bg-gray-200 hover:bg-gray-300 text-gray-800 rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-400 transition duration-200 flex items-center"
                                    @click="prevStep()">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                                    </svg>
                                    Précédent
                                </button>
                                
                                <button type="submit" 
                                    class="py-3 px-6 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition duration-200 flex items-center"
                                    :class="{'opacity-50 cursor-not-allowed': !finalValid, 'hover:bg-green-700': finalValid}"
                                    :disabled="!finalValid">
                                    Créer mon compte
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
                
                <!-- Footer -->
                <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 rounded-b-2xl">
                    <p class="text-sm text-gray-600 text-center">
                        Vous avez déjà un compte? <a href="{{ route('login') }}" class="font-medium text-indigo-600 hover:text-indigo-800 transition duration-200">Connectez-vous ici</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection