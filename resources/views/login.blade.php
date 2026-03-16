@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-5">
        <div class="card shadow-lg p-4" style="border-radius: 20px; margin-top: 50px;">
            <div class="text-center mb-4">
                <i class="bi bi-person-circle text-primary fs-1"></i>
                <h3 class="fw-bold mt-2">Identification</h3>
                <p class="text-muted">Veuillez vous connecter pour finaliser votre réservation</p>
            </div>

            <form action="{{ route('login.post') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label class="form-label fw-bold">Email ou Login</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-envelope"></i></span>
                        <input type="text" name="login" class="form-control bg-light border-0 py-2" placeholder="votre@email.com" required>
                    </div>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-0"><i class="bi bi-lock"></i></span>
                        <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="••••••••" required>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">Se connecter</button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-muted small">Vous n'avez pas de compte ? <a href="{{ route('register') }}" class="text-primary fw-bold">Créez-en un</a></p>
                <div class="alert alert-info py-2 small border-0">
                    <i class="bi bi-info-circle me-1"></i> Compte de test : user@oncf.ma / password
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
