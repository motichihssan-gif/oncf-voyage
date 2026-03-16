@extends('Master_page')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-10">
        <!-- Re-styled Search Box -->
        <div class="card shadow-lg p-4 mb-5 search-card" style="border-radius: 30px; border-top: 5px solid var(--oncf-orange);">
            <div class="d-flex align-items-center mb-4">
                <div class="text-white rounded-circle p-2 me-3 shadow-sm" style="background: var(--oncf-blue);">
                    <i class="bi bi-geo-alt-fill fs-4"></i>
                </div>
                <div>
                    <h4 class="mb-0 fw-bold">Où souhaitez-vous aller ?</h4>
                    <p class="text-muted small mb-0">Réservez votre billet Al Boraq ou Train Atlas en un clic</p>
                </div>
            </div>
            
            <form action="{{ route('voyage.search') }}" method="GET">
                <div class="row g-4">
                    <div class="col-lg-5">
                        <label class="form-label text-muted small fw-bold">DÉPART</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-cursor-fill text-primary"></i></span>
                            <select name="ville_depart" class="form-select border-0 py-3 px-4" style="border-radius: 0 15px 15px 0; background: rgba(255,255,255,0.5);" required>
                                <option value="">Ville de départ</option>
                                @foreach($villesDepart as $v)
                                    <option value="{{ $v }}" {{ (isset($vd) && $vd == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-5">
                        <label class="form-label text-muted small fw-bold">ARRIVÉE</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-0"><i class="bi bi-geo-fill text-danger"></i></span>
                            <select name="ville_arrivee" class="form-select border-0 py-3 px-4" style="border-radius: 0 15px 15px 0; background: rgba(255,255,255,0.5);" required>
                                <option value="">Ville d'arrivée</option>
                                @foreach($villesArrivee as $v)
                                    <option value="{{ $v }}" {{ (isset($va) && $va == $v) ? 'selected' : '' }}>{{ $v }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-lg-2 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary w-100 py-3 rounded-pill shadow-sm">
                            <i class="bi bi-search me-2"></i> Chercher
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($voyages))
            <h5 class="mb-4 fw-bold"><i class="bi bi-train-front me-2 text-primary"></i> Résultats de recherche :</h5>
            <div class="row g-4 mb-5">
                @forelse($voyages as $voyage)
                    <div class="col-md-12">
                        <div class="card trip-card transition-all shadow-sm">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-lg-2 text-center text-lg-start mb-3 mb-lg-0">
                                        <div class="badge-oncf mb-2">{{ $voyage->code_voyage }}</div>
                                        <div class="text-muted small">Type: {{ str_contains($voyage->code_voyage, 'BORAQ') ? 'Al Boraq (LGV)' : 'Train Atlas' }}</div>
                                    </div>
                                    <div class="col-lg-6 mb-3 mb-lg-0">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="text-center">
                                                <h4 class="mb-0 fw-bold">{{ date('H:i', strtotime($voyage->heureDepart)) }}</h4>
                                                <div class="text-secondary">{{ $voyage->villeDepart }}</div>
                                            </div>
                                            <div class="flex-grow-1 mx-4 text-center position-relative">
                                                <div style="height: 2px; background: #eee; width: 100%; position: absolute; top: 50%;"></div>
                                                <i class="bi bi-train-front text-primary position-relative bg-white px-2"></i>
                                            </div>
                                            <div class="text-center">
                                                <h4 class="mb-0 fw-bold">{{ date('H:i', strtotime($voyage->heureDarrivee)) }}</h4>
                                                <div class="text-secondary">{{ $voyage->villeDarrivee }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-4 text-center text-lg-end">
                                        <div class="mb-2">
                                            @if($voyage->prixPromo)
                                                <span class="text-decoration-line-through text-muted small me-2">{{ $voyage->prixVoyage }} MAD</span>
                                                <span class="fs-3 fw-bold text-danger">{{ $voyage->prixPromo }}</span> <span class="text-danger fw-bold">MAD</span>
                                                <div class="badge bg-danger rounded-pill mb-2 ms-2">PROMO</div>
                                            @else
                                                <span class="fs-3 fw-bold text-primary">{{ $voyage->prixVoyage }}</span> <span class="text-muted">MAD</span>
                                            @endif
                                        </div>
                                        <form action="{{ route('cart.add') }}" method="POST" class="d-flex justify-content-center justify-content-lg-end align-items-center">
                                            @csrf
                                            <input type="hidden" name="voyage_id" value="{{ $voyage->id }}">
                                            <div class="input-group input-group-sm me-2" style="width: 100px;">
                                                <span class="input-group-text bg-white">Pass.</span>
                                                <input type="number" name="qte" value="1" min="1" class="form-control">
                                            </div>
                                            <button type="submit" class="btn btn-primary rounded-pill px-4 shadow-sm">Réserver</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center py-5">
                        <img src="https://illustrations.popsy.co/amber/no-results-found.svg" height="200" class="mb-4">
                        <h5 class="text-muted">Aucun trajet trouvé pour cet itinéraire.</h5>
                        <p class="text-secondary">Essayez d'autres villes ou horaires.</p>
                    </div>
                @endforelse
            </div>
        @endif

        @if(isset($promos) && !isset($voyages))
            <section class="mb-5 py-5">
                <div class="text-center mb-5">
                    <span class="badge bg-danger px-3 py-2 rounded-pill mb-3 shadow-sm"><i class="bi bi-fire me-1"></i> OFFRES FLASH</span>
                    <h2 class="fw-bold">Les meilleures promos ONCF</h2>
                    <p class="text-muted">Voyagez à prix réduit avec nos offres exclusives de la semaine.</p>
                </div>
                <div class="row g-4">
                    @foreach($promos as $promo)
                        <div class="col-md-4">
                            <div class="card promo-card h-100 border-0 shadow-sm overflow-hidden" style="border-radius: 20px;">
                                <div class="promo-header p-3 text-white text-center" style="background: linear-gradient(45deg, #ff416c, #ff4b2b);">
                                    <h5 class="mb-0 fw-bold">{{ $promo->villeDepart }} <i class="bi bi-arrow-right mx-2"></i> {{ $promo->villeDarrivee }}</h5>
                                </div>
                                <div class="card-body p-4 text-center">
                                    <div class="mb-2 text-muted small">Code: {{ $promo->code_voyage }}</div>
                                    <div class="d-flex justify-content-center align-items-center mb-3">
                                        <span class="text-decoration-line-through text-muted me-2">{{ $promo->prixVoyage }} MAD</span>
                                        <span class="fs-2 fw-bold text-danger">{{ $promo->prixPromo }}</span> <span class="text-danger fw-bold ms-1">MAD</span>
                                    </div>
                                    <a href="{{ route('voyage.search', ['ville_depart' => $promo->villeDepart, 'ville_arrivee' => $promo->villeDarrivee]) }}" class="btn btn-outline-danger w-100 rounded-pill fw-bold">Profiter de l'offre</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
        @endif
        
        <!-- REDESIGNED MARKETING SECTIONS -->

      

        <!-- Section: Destinations Tendances (Mosaic Style) -->
        <section class="py-5 mt-5 section-with-bg-alt">
            <div class="text-center mb-5">
                <h2 class="display-6 fw-bold">Évadez-vous le <span class="text-primary">temps d'un weekend</span></h2>
                <div class="mx-auto bg-primary rounded-pill mb-3" style="width: 60px; height: 4px;"></div>
            </div>
            
            <div class="destination-mosaic">
                <div class="mosaic-item large">
                    <div class="destination-overlap-card rounded-5 overflow-hidden shadow-lg">
                        <img src="/casablanca_p.png" class="img-fluid" alt="Casablanca">
                        <div class="content p-4 text-white">
                            <span class="badge bg-blur mb-2 px-3">LA PLUS POPULAIRE</span>
                            <h3 class="fw-bold">Casablanca</h3>
                            <p class="small mb-3">La métropole économique et son architecture monumentale.</p>
                            <a href="#" class="btn btn-light btn-sm rounded-pill px-4 fw-bold">Explorer</a>
                        </div>
                    </div>
                </div>
                <div class="mosaic-item small-1">
                    <div class="destination-overlap-card rounded-5 overflow-hidden shadow-sm h-100">
                        <img src="/marrakech.png" class="img-fluid h-100 object-fit-cover" alt="Marrakech">
                        <div class="content p-4 text-white">
                            <h4 class="fw-bold">Marrakech</h4>
                            <a href="#" class="text-white small fw-bold">Voir départs <i class="bi bi-chevron-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="mosaic-item small-2">
                    <div class="destination-overlap-card rounded-5 overflow-hidden shadow-sm h-100" style="background: rgba(0,0,0,0.1);">
                        <img src="https://media.tacdn.com/media/attractions-splice-spp-674x446/12/37/e8/62.jpg" class="img-fluid h-100 object-fit-cover" alt="Tanger">
                        <div class="content p-4 text-white">
                            <h4 class="fw-bold text-shadow">Tanger</h4>
                            <p class="small mb-0">La perle du Détroit.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: FAQ (Modern Cards) -->
        <section class="py-5 mt-5 section-with-bg-alt">
            <div class="row">
                <div class="col-lg-4">
                    <h2 class="fw-bold display-6 mb-4">Besoin d'aide ?</h2>
                    <p class="text-muted mb-4">Nous répondons à vos questions les plus fréquentes pour faciliter votre voyage.</p>
                    <div class="card border-0 bg-primary text-black p-4 rounded-4 shadow-sm mb-4">
                        <h5 class="fw-bold mb-3">Support Prioritaire</h5>
                        <p class="small opacity-75 mb-3">Notre équipe est disponible 24/7 pour toute assistance en gare ou en ligne.</p>
                        <a href="#" class="btn btn-outline-light btn-sm rounded-pill w-fit px-4">Nous contacter</a>
                    </div>
                </div>
                <div class="col-lg-8">
                    <div class="row g-4">
                        <div class="col-md-6">
                            <div class="faq-card-premium p-4 rounded-4 shadow-sm h-100 border-start border-4 border-primary">
                                <h6 class="fw-bold text-dark mb-3">Comment annuler mon billet ?</h6>
                                <p class="small text-muted mb-0">L'annulation se fait via votre espace client ou directement au guichet jusqu'à 2h avant le départ.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="faq-card-premium p-4 rounded-4 shadow-sm h-100">
                                <h6 class="fw-bold text-dark mb-3">Bagages autorisés ?</h6>
                                <p class="small text-muted mb-0">Chaque voyageur peut emporter 2 valises et un sac à main. Des espaces bagages sont prévus à chaque voiture.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="faq-card-premium p-4 rounded-4 shadow-sm h-100">
                                <h6 class="fw-bold text-dark mb-3">Wi-Fi à bord ?</h6>
                                <p class="small text-muted mb-0">Al Boraq offre un Wi-Fi gratuit en 1ère classe. Les trains Atlas sont équipés progressivement.</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="faq-card-premium p-4 rounded-4 shadow-sm h-100">
                                <h6 class="fw-bold text-dark mb-3">Enfants de -4 ans ?</h6>
                                <p class="small text-muted mb-0">Le voyage est gratuit pour les enfants de moins de 4 ans ne occupant pas de siège séparé.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Section: Avis (Premium Testimonial) -->
        <section class="py-5 mt-5 position-relative overflow-hidden section-with-bg">
            <div class="testimonial-orb-1"></div>
            <div class="testimonial-orb-2"></div>
            
            <div class="container position-relative py-5">
                <div class="text-center mb-5">
                    <h2 class="display-6 fw-bold">Ce que disent <span class="text-primary">nos voyageurs</span></h2>
                </div>
                <div class="row g-4 justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg p-4 rounded-5 position-relative h-100 review-card">
                            <div class="quote-icon mb-2 opacity-25 text-primary"><i class="bi bi-quote fs-1"></i></div>
                            <p class="small fst-italic text-muted">"Un service exceptionnel ! J'ai réservé mon billet en quelques secondes. Le design du site est magnifique et très intuitif."</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person-fill text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Motich Ihsane</h6>
                                    <div class="text-warning x-small">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg p-4 rounded-5 position-relative h-100 review-card" style="background: rgba(255, 255, 255, 0.6) !important; backdrop-filter: blur(10px);">
                            <div class="quote-icon mb-2 opacity-25 text-primary"><i class="bi bi-quote fs-1"></i></div>
                            <p class="small fst-italic text-muted">"L'expérience Al Boraq avec cette plateforme est parfaite. Enfin un site moderne pour nos voyages au Maroc. Je recommande !"</p>
                            <div class="d-flex align-items-center mt-3">
                                <div class="bg-primary-soft rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
                                    <i class="bi bi-person-fill text-primary"></i>
                                </div>
                                <div>
                                    <h6 class="fw-bold mb-0">Baidouch Bouchra</h6>
                                    <div class="text-warning x-small">
                                        <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </div>
</div>

<style>
    /* CORE COMPONENTS */
    .search-card {
        margin-top: -50px;
        position: relative;
        z-index: 100;
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.2);
    }
    
    .promo-card {
        transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        border: 1px solid rgba(255, 65, 108, 0.1) !important;
    }
    .promo-card:hover {
        transform: translateY(-15px) scale(1.03);
        box-shadow: 0 20px 40px rgba(255, 65, 108, 0.2) !important;
    }
    
    .trip-card {
        border-radius: 20px;
        border-left: 5px solid var(--oncf-blue);
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .trip-card:hover {
        transform: scale(1.01);
        box-shadow: 0 15px 40px rgba(0,0,0,0.08) !important;
    }
    
    .badge-oncf {
        background: #eef2f7;
        color: var(--oncf-blue);
        padding: 5px 12px;
        border-radius: 8px;
        font-weight: bold;
        display: inline-block;
    }

    /* PREMIUM MARKETING SECTIONS */
    .ls-2 { letter-spacing: 2px; }
    .w-fit { width: fit-content; }
    .x-small { font-size: 0.75rem; }
    
    .section-with-bg {
        background: rgba(255, 255, 255, 0.15) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 10px 30px rgba(0,0,0,0.05);
        position: relative;
        overflow: hidden;
        border-radius: 60px;
        padding: 60px 40px;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }
    
    .section-with-bg-alt {
        background: rgba(255, 255, 255, 0.1) !important;
        backdrop-filter: blur(10px);
        border-radius: 60px;
        padding: 60px 40px;
        position: relative;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .feature-card-premium {
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.4);
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(10px);
    }
    
    .review-card {
        background: rgba(255, 255, 255, 0.6) !important;
        backdrop-filter: blur(10px);
        transition: transform 0.3s ease;
    }
    
    .faq-card-premium {
        background: rgba(255, 255, 255, 0.7) !important;
        backdrop-filter: blur(10px);
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.4);
    }

    .icon-circle {
        background: rgba(255, 255, 255, 0.2) !important;
    }
    .icon-circle i {
        color: white !important;
    }
    .icon-sm {
        width: 45px !important;
        height: 45px !important;
        border-radius: 12px !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        background: #f0f7ff;
        border-radius: 20px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .destination-mosaic {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        grid-template-rows: repeat(2, 250px);
        gap: 15px;
    }
    .mosaic-item.large {
        grid-column: 1 / 2;
        grid-row: 1 / 3;
    }
    .destination-overlap-card {
        position: relative;
        height: 100%;
    }
    .destination-overlap-card img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.8s ease;
    }
    .destination-overlap-card:hover img {
        transform: scale(1.1);
    }
    .destination-overlap-card .content {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: linear-gradient(transparent, rgba(0,0,0,0.9));
        z-index: 2;
        padding: 1.5rem !important;
    }
    .bg-blur {
        background: rgba(255,255,255,0.2);
        backdrop-filter: blur(5px);
    }
    
    .review-card {
        transition: transform 0.3s ease;
    }
    .review-card:hover {
        transform: scale(1.02);
    }
    
    .testimonial-orb-1 {
        position: absolute;
        width: 300px;
        height: 300px;
        background: radial-gradient(circle, rgba(0,70,148,0.05) 0%, transparent 70%);
        top: -100px;
        right: -100px;
    }
    .testimonial-orb-2 {
        position: absolute;
        width: 400px;
        height: 400px;
        background: radial-gradient(circle, rgba(255,102,0,0.05) 0%, transparent 70%);
        bottom: -150px;
        left: -150px;
    }
    
    .text-shadow {
        text-shadow: 0 2px 4px rgba(0,0,0,0.5);
    }
    
    @media (max-width: 991px) {
        .destination-mosaic {
            grid-template-columns: 1fr;
            grid-template-rows: auto;
        }
        .mosaic-item.large {
            grid-row: auto;
            height: 400px;
        }
    }
</style>
@endsection
