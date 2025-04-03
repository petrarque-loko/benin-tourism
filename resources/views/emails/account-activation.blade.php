@component('mail::message')
# Activation de votre compte

Bonjour {{ $user->prenom }} {{ $user->nom }},

Merci de vous être inscrit sur la plateforme Tourisme Bénin. Pour activer votre compte, veuillez cliquer sur le bouton ci-dessous.

@component('mail::button', ['url' => route('auth.activate', $user->activation_token)])
Activer mon compte
@endcomponent

Ce lien expire dans 24 heures.

Merci,<br>
L'équipe {{ config('app.name') }}
@endcomponent