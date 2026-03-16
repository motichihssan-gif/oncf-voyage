@extends('Master_page')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Mon Panier</h5>
        <a href="{{ route('voyage.form') }}" class="btn btn-sm btn-outline-secondary">Continuer mes achats</a>
    </div>
    <div class="card-body p-0">
        @if(count($cart) > 0)
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Voyage</th>
                            <th>Prix</th>
                            <th>Quantité</th>
                            <th>Sous-total</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $total = 0 @endphp
                        @foreach($cart as $id => $details)
                            @php $total += $details['prix'] * $details['qte'] @endphp
                            <tr data-id="{{ $id }}">
                                <td>
                                    <strong>{{ $details['code_voyage'] }}</strong><br>
                                    <small class="text-muted">{{ $details['villeDepart'] }} → {{ $details['villeArrivee'] }}</small>
                                </td>
                                <td>{{ $details['prix'] }} DH</td>
                                <td>
                                    <input type="number" value="{{ $details['qte'] }}" class="form-control form-control-sm update-cart" style="width: 70px;" min="1">
                                </td>
                                <td>{{ $details['prix'] * $details['qte'] }} DH</td>
                                <td>
                                    <button class="btn btn-sm btn-danger remove-from-cart">Supprimer</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="3" class="text-end"><strong>Total :</strong></td>
                            <td colspan="2"><strong>{{ $total }} DH</strong></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
            <div class="p-3 text-end">
                <a href="{{ route('checkout.voyageurs') }}" class="btn btn-primary">Passer au paiement</a>
            </div>
        @else
            <div class="p-5 text-center text-muted">
                <p class="mb-0">Votre panier est vide.</p>
            </div>
        @endif
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(".update-cart").change(function (e) {
        e.preventDefault();
        var ele = $(this);
        $.ajax({
            url: '{{ route('cart.update') }}',
            method: "patch",
            data: {
                _token: '{{ csrf_token() }}', 
                id: ele.parents("tr").attr("data-id"), 
                qte: ele.val()
            },
            success: function (response) {
               window.location.reload();
            }
        });
    });

    $(".remove-from-cart").click(function (e) {
        e.preventDefault();
        var ele = $(this);
        if(confirm("Voulez-vous vraiment supprimer cet article ?")) {
            $.ajax({
                url: '{{ route('cart.remove') }}',
                method: "DELETE",
                data: {
                    _token: '{{ csrf_token() }}', 
                    id: ele.parents("tr").attr("data-id")
                },
                success: function (response) {
                    window.location.reload();
                }
            });
        }
    });
</script>
@endsection
