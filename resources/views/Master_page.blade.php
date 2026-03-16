<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ONCF Voyage Search - Trouvez votre trajet en 5 secondes</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <style>
        :root {
            --oncf-blue: #004694;
            --oncf-orange: #ff6a00;
            --light-bg: #f4f7fa;
            --premium-grey: #6c757d;
        }

        html {
            height: 100%;
            background: #f4f7fa;
        }
        
        body {
            font-family: 'Outfit', sans-serif;
            background: transparent !important;
            min-height: 100vh;
            margin: 0;
            padding: 0;
        }

        .main-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: linear-gradient(rgba(255, 255, 255, 0.3), rgba(255, 255, 255, 0.3)), 
                              url('https://visiterlemarocfacile.com/wp-content/uploads/2025/10/GARE-TRAIN-scaled.jpg');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            z-index: -1;
        }

        .content-wrapper {
            position: relative;
            z-index: 1;
        }

        .navbar {
            background: rgba(255, 255, 255, 0.8) !important;
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.05);
            padding: 15px 0;
        }

        .navbar-brand {
            font-weight: 700;
            color: var(--oncf-blue) !important;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .navbar-brand img {
            border-radius: 8px;
        }

        .btn-primary {
            background-color: var(--oncf-blue);
            border: none;
            padding: 10px 25px;
            border-radius: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #003366;
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0,70,148,0.2);
        }

        .card {
            border: none;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.04);
            overflow: hidden;
            background: rgba(255, 255, 255, 0.7) !important;
            backdrop-filter: blur(5px);
        }

        .hero-section {
            background: linear-gradient(rgba(0, 45, 90, 0.2), rgba(0, 45, 90, 0.3)), url('/train_bg.png');
            background-size: cover;
            background-position: center;
            padding: 120px 0;
            margin-bottom: -150px;
            color: white;
            text-align: center;
            border-radius: 0 0 50px 50px;
            backdrop-filter: blur(3px);
        }

        .hero-section h1 {
            font-weight: 800;
            font-size: 3.5rem;
            margin-bottom: 15px;
            text-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }

        .hero-section p {
            font-weight: 300;
            font-size: 1.4rem;
            letter-spacing: 1px;
            text-shadow: 0 1px 5px rgba(0,0,0,0.3);
        }

        .footer {
            margin-top: 100px;
            padding: 40px 0;
            background: rgba(255, 255, 255, 0.3);
            backdrop-filter: blur(10px);
            border-top: 1px solid rgba(0,0,0,0.05);
            text-align: center;
            color: var(--premium-grey);
        }
        .footer a {
            color: var(--oncf-blue);
            font-weight: 600;
        }
        .footer a:hover {
            color: var(--oncf-orange);
        }

        /* Micro-animations */
        .nav-link {
            position: relative;
            font-weight: 500;
            margin: 0 10px;
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 50%;
            width: 0;
            height: 2px;
            background: var(--oncf-blue);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }

        .nav-link:hover::after {
            width: 100%;
        }

        .badge-oncf {
            background: rgba(0,70,148,0.1);
            color: var(--oncf-blue);
            font-weight: 600;
            padding: 8px 15px;
            border-radius: 10px;
        }
    </style>
</head>
<body>
    <div class="main-background"></div>
    <div class="content-wrapper">
    <nav class="navbar navbar-expand-lg navbar-light sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="/oncf_voyage_logo.png" alt="Logo" height="40" onerror="this.src='https://upload.wikimedia.org/wikipedia/commons/e/ee/ONCF_Logo.svg'">
                <span>ONCF Voyage Search</span>
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="{{ route('voyage.form') }}"><i class="bi bi-search me-1"></i> Rechercher</a></li>
                    <li class="nav-item"><a class="nav-link" href="{{ route('cart.show') }}"><i class="bi bi-cart3 me-1"></i> Panier</a></li>
                    @auth
                        <li class="nav-item"><a class="nav-link" href="{{ route('historique') }}"><i class="bi bi-clock-history me-1"></i> Mes Réservations</a></li>
                        <li class="nav-item"><a class="nav-link text-danger" href="{{ route('logout') }}"><i class="bi bi-box-arrow-right me-1"></i> Déconnexion</a></li>
                        <li class="nav-item ms-lg-3"><span class="btn btn-outline-primary rounded-pill"><i class="bi bi-person-circle me-1"></i> {{ auth()->user()->nom }}</span></li>
                    @else
                        <li class="nav-item ms-lg-3"><a class="btn btn-primary rounded-pill" href="{{ route('login') }}"><i class="bi bi-person-circle me-1"></i> Connexion</a></li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <div class="hero-section">
        <div class="container">
            <h1>Voyagez à travers le Maroc</h1>
            <p>Trouvez votre trajet en 5 secondes</p>
        </div>
    </div>
<br><br><br><br>

    <div class="container" style="position: relative; z-index: 10;">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @yield('content')
    </div>

    <footer class="footer">
        <div class="container">
            <p class="mb-0">&copy; {{ date('Y') }} ONCF Voyage Search - Direction du Transport Voyageurs</p>
            <div class="mt-2" style="font-size: 0.9rem;">
               
                <a href="#" class="text-decoration-none mx-2">Contact</a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </div> <!-- Close content-wrapper -->
</body>
</html>
