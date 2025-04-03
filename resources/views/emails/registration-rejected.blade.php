@component('mail::message')
# Inscription rejetée

Bonjour {{ $user->prenom }} {{ $user->nom }},

Nous sommes désolés de vous informer que votre inscription en tant que {{ $user->role->name }} sur la plateforme Tourisme Bénin a été rejetée pour la raison suivante :

"{{ $reason }}"

Vous pouvez soumettre une nouvelle demande d'inscription en corrigeant les problèmes mentionnés ci-dessus.

@component('mail::button', ['url' => route('register')])
Nouvelle inscription
@endcomponent

Merci,<br>
L'équipe {{ config('app.name') }}
@endcomponent