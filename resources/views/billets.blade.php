@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <div class="d-flex justify-content-between align-items-center mb-4 d-print-none">
            <h3>Mes Billets - Commande #{{ $commande->id }}</h3>
            <button onclick="window.print()" class="btn btn-dark">Imprimer les billets</button>
        </div>

        <div class="alert alert-info border-0 shadow-sm mb-4">
            <strong>Client :</strong> {{ $commande->client->nom }} {{ $commande->client->prenom }}<br>
            <strong>Date Commande :</strong> {{ $commande->date_comm }}
        </div>

        @foreach($commande->voyages as $voyage)
            <div class="card shadow-sm mb-4 border-0 ticket-card">
                <div class="card-body p-0 overflow-hidden rounded shadow-sm border">
                    <div class="bg-primary text-white p-2 px-4 d-flex justify-content-between align-items-center">
                        <span>Billet ONCF - Al Boraq</span>
                        <img src="https://upload.wikimedia.org/wikipedia/commons/e/ee/ONCF_Logo.svg" height="25" filter="invert(1)">
                    </div>
                    <div class="p-4 bg-white">
                        <div class="row align-items-center">
                            <div class="col-md-3 text-center border-end py-3">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=100x100&data={{ $voyage->id }}-{{ $commande->id }}" alt="QR Code" class="mb-2">
                                <br>
                                <small class="text-muted">Scanner le billet</small>
                            </div>
                            <div class="col-md-6 px-4">
                                <div class="mb-3">
                                    <h6 class="text-muted mb-1">VOYAGEUR :</h6>
                                    <h5 class="mb-0">{{ $voyage->pivot->nom_voyageur }}</h5>
                                    <small class="text-muted">Passport: {{ $voyage->pivot->passport_voyageur }}</small>
                                </div>
                                <div class="d-flex justify-content-between align-items-center mb-0">
                                    <div>
                                        <h6 class="text-muted mb-1">DÉPART :</h6>
                                        <h5 class="mb-0 text-primary">{{ $voyage->villeDepart }}</h5>
                                        <small class="badge bg-light text-dark">{{ $voyage->heureDepart }}</small>
                                    </div>
                                    <div class="text-muted px-3"><i class="bi bi-arrow-right"></i> → </div>
                                    <div class="text-end">
                                        <h6 class="text-muted mb-1">ARRIVÉE :</h6>
                                        <h5 class="mb-0 text-primary">{{ $voyage->villeDarrivee }}</h5>
                                        <small class="badge bg-light text-dark">{{ $voyage->heureDarrivee }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3 text-center border-start py-3">
                                <h6 class="text-muted mb-1">VOYAGE :</h6>
                                <h4 class="mb-2">{{ $voyage->code_voyage }}</h4>
                                <h4 class="mb-0 text-success">{{ $voyage->prixVoyage }} DH</h4>
                                <small class="text-muted">Date: {{ date('Y-m-d') }}</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="text-center mt-5 d-print-none">
            <a href="/" class="btn btn-outline-primary">Retour à l'accueil</a>
        </div>
    </div>
</div>

<style>
    @media print {
        .d-print-none { display: none !important; }
        .card { border: 1px solid #ddd !important; box-shadow: none !important; }
    }
</style>
@endsection
