<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\VoyageController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Artisan;

// ROUTE MAGIQUE POUR L'INSTALLATION (A supprimer après)
Route::get('/setup-database', function () {
    try {
        Artisan::call('migrate:fresh --seed --force');
        return "✅ Base de données AlwaysData installée avec succès ! <a href='/'>Retour à l'accueil</a>";
    } catch (\Exception $e) {
        return "❌ Erreur : " . $e->getMessage();
    }
});

Route::get('/', function () {
    return redirect()->route('voyage.form');
});

Route::get('/rechercher', [VoyageController::class, 'formRecherche'])->name('voyage.form');
Route::get('/rechercher/resultats', [VoyageController::class, 'resultatRecherche'])->name('voyage.search');

Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::get('/cart', [CartController::class, 'show'])->name('cart.show');
Route::patch('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/checkout/voyageurs', [CartController::class, 'formVoyageurs'])->name('checkout.voyageurs');
Route::post('/checkout/pay', [CartController::class, 'processCheckout'])->name('checkout.pay');
Route::get('/billets', [CartController::class, 'showBillets'])->name('billets.show');

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
Route::post('/register', [AuthController::class, 'register'])->name('register.post');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/historique', [AuthController::class, 'history'])->name('historique')->middleware('auth');
