@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Informations des Voyageurs</h5>
            </div>
            <div class="card-body">
                <form action="{{ route('checkout.pay') }}" method="POST">
                    @csrf
                    @php $i = 1 @endphp
                    @foreach($cart as $id => $details)
                        @for($q = 1; $q <= $details['qte']; $q++)
                            <div class="border rounded p-3 mb-3 bg-light">
                                <h6 class="text-primary border-bottom pb-2">Passager #{{ $i++ }} - {{ $details['code_voyage'] }} ({{ $details['villeDepart'] }} → {{ $details['villeArrivee'] }})</h6>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">Nom Complet</label>
                                        <input type="text" name="passagers[{{ $i }}][nom]" class="form-control" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Numéro de passeport</label>
                                        <input type="text" name="passagers[{{ $i }}][passport]" class="form-control" required placeholder="Ex: KF123456">
                                    </div>
                                    <input type="hidden" name="passagers[{{ $i }}][voyage_id]" value="{{ $id }}">
                                </div>
                            </div>
                        @endfor
                    @endforeach

                    <div class="border rounded p-3 mb-3 bg-white shadow-sm">
                        <h6 class="text-dark border-bottom pb-2"><i class="bi bi-credit-card"></i> Paiement par carte</h6>
                        <div class="mb-3">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/5/5e/Visa_Inc._logo.svg" height="20" class="me-2">
                            <img src="https://upload.wikimedia.org/wikipedia/commons/2/2a/Mastercard-logo.svg" height="20">
                        </div>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <label class="form-label">Numéro de carte</label>
                                <input type="text" class="form-control" required placeholder="XXXX XXXX XXXX XXXX">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Date d'expiration</label>
                                <input type="text" class="form-control" required placeholder="MM/YY">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">CVV</label>
                                <input type="text" class="form-control" required placeholder="123">
                            </div>
                        </div>
                    </div>
                    
                    <div class="mt-4 border-top pt-3 text-end">
                        <button type="submit" class="btn btn-success btn-lg px-5">Confirmer et Payer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
