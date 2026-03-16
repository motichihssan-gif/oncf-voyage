@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="card shadow-lg p-4" style="border-radius: 20px;">
            <div class="d-flex align-items-center mb-4">
                <div class="bg-primary text-white rounded-circle p-2 me-3">
                    <i class="bi bi-clock-history fs-4"></i>
                </div>
                <h4 class="mb-0 fw-bold">Mon Historique de Réservations</h4>
            </div>

            @forelse($commandes as $commande)
                <div class="card mb-4 border-0 shadow-sm" style="background: #f8f9fa; border-radius: 15px;">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center border-0 p-3">
                        <div>
                            <span class="text-muted small fw-bold">COMMANDE #{{ $commande->id }}</span><br>
                            <span class="fw-bold text-dark">{{ date('d/m/Y à H:i', strtotime($commande->date_comm)) }}</span>
                        </div>
                        <div class="text-end">
                            <span class="badge bg-success rounded-pill px-3">Confirmée</span>
                        </div>
                    </div>
                    <div class="card-body p-3">
                        @foreach($commande->voyages as $voyage)
                            <div class="d-flex align-items-center mb-2 p-2 bg-white rounded shadow-sm">
                                <div class="me-3">
                                    <i class="bi bi-train-light-front text-primary fs-3"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <h6 class="mb-0 fw-bold">{{ $voyage->villeDepart }} → {{ $voyage->villeDarrivee }}</h6>
                                    <small class="text-muted">{{ $voyage->code_voyage }} | Départ: {{ date('H:i', strtotime($voyage->heureDepart)) }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="fw-bold text-primary">{{ $voyage->prixVoyage }} MAD</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-5">
                    <img src="https://illustrations.popsy.co/amber/shrugging.svg" height="150" class="mb-3">
                    <h5 class="text-muted">Vous n'avez pas encore effectué de réservation.</h5>
                    <a href="{{ route('voyage.form') }}" class="btn btn-primary rounded-pill mt-3">Commencer à voyager</a>
                </div>
            @endforelse
        </div>
    </div>
</div>
@endsection
