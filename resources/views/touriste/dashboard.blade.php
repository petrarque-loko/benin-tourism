@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Tableau de Bord du Touriste</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">Statistiques</div>
                <div class="card-body">
                    <p>Total des Réservations: {{ $totalReservations }}</p>
                    <p>Sites Visités: {{ $sitesVisites }}</p>
                </div>
            </div>
        </div>

        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">Prochaines Réservations</div>
                <div class="card-body">
                    @if($prochaines->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Site/Activité</th>
                                    <th>Guide</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($prochaines as $reservation)
                                <tr>
                                    <td>{{ $reservation->date_debut }} - {{ $reservation->date_fin }}</td>
                                    <td>{{ $reservation->reservable->nom }}</td>
                                    <td>{{ $reservation->guide->name }}</td>
                                    <td>{{ $reservation->statut }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Pas de réservations à venir.</p>
                    @endif
                </div>
            </div>

            <div class="card">
                <div class="card-header">Dernières Réservations</div>
                <div class="card-body">
                    @if($dernieres->count() > 0)
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Site/Activité</th>
                                    <th>Guide</th>
                                    <th>Statut</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($dernieres as $reservation)
                                <tr>
                                    <td>{{ $reservation->date_debut }} - {{ $reservation->date_fin }}</td>
                                    <td>{{ $reservation->reservable->nom }}</td>
                                    <td>{{ $reservation->guide->name }}</td>
                                    <td>{{ $reservation->statut }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p>Pas de réservations récentes.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection