@component('mail::message')
# Inscription approuvée

Bonjour {{ $user->prenom }} {{ $user->nom }},

Nous avons le plaisir de vous informer que votre inscription en tant que {{ $user->role->name }} sur la plateforme Tourisme Bénin a été approuvée.

Vous pouvez maintenant vous connecter à votre compte et commencer à utiliser nos services.

@component('mail::button', ['url' => route('login')])
Se connecter
@endcomponent

Merci,<br>
L'équipe {{ config('app.name') }}
@endcomponent