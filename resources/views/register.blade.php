@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card shadow-lg p-4" style="border-radius: 20px; margin-top: 30px;">
            <div class="text-center mb-4">
                <i class="bi bi-person-plus text-primary fs-1"></i>
                <h3 class="fw-bold mt-2">Créer un compte</h3>
                <p class="text-muted">Rejoignez ONCF Voyage Search pour gérer vos trajets</p>
            </div>

            <form action="{{ route('register.post') }}" method="POST">
                @csrf
                <div class="row g-3">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Nom</label>
                        <input type="text" name="nom" class="form-control bg-light border-0 py-2" placeholder="Nom" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Prénom</label>
                        <input type="text" name="prenom" class="form-control bg-light border-0 py-2" placeholder="Prénom" required>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Login (Identifiant)</label>
                    <input type="text" name="login" class="form-control bg-light border-0 py-2" placeholder="Ex: amine92" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Email</label>
                    <input type="email" name="email" class="form-control bg-light border-0 py-2" placeholder="votre@email.com" required>
                </div>
                <div class="mb-3">
                    <label class="form-label fw-bold">Téléphone</label>
                    <input type="text" name="tel" class="form-control bg-light border-0 py-2" placeholder="06XXXXXXXX" required>
                </div>
                <div class="mb-4">
                    <label class="form-label fw-bold">Mot de passe</label>
                    <input type="password" name="password" class="form-control bg-light border-0 py-2" placeholder="••••••••" required>
                </div>
                <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold">S'inscrire</button>
            </form>
            
            <div class="mt-4 text-center">
                <p class="text-muted small">Vous avez déjà un compte ? <a href="{{ route('login') }}" class="text-primary fw-bold">Connectez-vous</a></p>
            </div>
        </div>
    </div>
</div>
@endsection
