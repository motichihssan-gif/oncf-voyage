<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Voyage;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $voyage = Voyage::findOrFail($request->voyage_id);
        $cart = session()->get('cart', []);

        if (isset($cart[$voyage->id])) {
            $cart[$voyage->id]['qte'] += $request->qte;
        } else {
            $cart[$voyage->id] = [
                "code_voyage" => $voyage->code_voyage,
                "qte" => $request->qte,
                "prix" => $voyage->prixPromo ?? $voyage->prixVoyage,
                "villeDepart" => $voyage->villeDepart,
                "villeArrivee" => $voyage->villeDarrivee
            ];
        }

        session()->put('cart', $cart);
        return redirect()->route('cart.show')->with('success', 'Voyage ajouté au panier !');
    }

    public function show()
    {
        $cart = session()->get('cart', []);
        return view('panier', compact('cart'));
    }

    public function update(Request $request)
    {
        if ($request->id && $request->qte) {
            $cart = session()->get('cart');
            $cart[$request->id]["qte"] = $request->qte;
            session()->put('cart', $cart);
            return response()->json(['success' => true]);
        }
    }

    public function remove(Request $request)
    {
        if ($request->id) {
            $cart = session()->get('cart');
            if (isset($cart[$request->id])) {
                unset($cart[$request->id]);
                session()->put('cart', $cart);
            }
            return response()->json(['success' => true]);
        }
    }

    public function formVoyageurs()
    {
        if (!auth()->check()) {
            return redirect()->route('login')->with('info', 'Veuillez vous identifier pour continuer.');
        }

        $cart = session()->get('cart', []);
        if (empty($cart)) return redirect()->route('voyage.form');
        return view('voyageurs', compact('cart'));
    }

    public function processCheckout(Request $request)
    {
        $cart = session()->get('cart');
        if (!$cart) return redirect()->route('voyage.form');

        $commande = \App\Models\Commande::create([
            'id_client' => auth()->id(),
            'date_comm' => now()
        ]);

        if ($request->has('passagers')) {
            foreach ($request->passagers as $p) {
                \App\Models\Billet::create([
                    'id_voyage' => $p['voyage_id'],
                    'id_commande' => $commande->id,
                    'qte' => 1,
                    'nom_voyageur' => $p['nom'],
                    'passport_voyageur' => $p['passport']
                ]);
            }
        }

        session()->forget('cart');
        session()->put('last_commande_id', $commande->id);

        return redirect()->route('billets.show')->with('success', 'Réservation effectuée avec succès !');
    }

    public function showBillets()
    {
        $commandeId = session()->get('last_commande_id');
        if (!$commandeId) return redirect()->route('voyage.form');

        $commande = \App\Models\Commande::with(['voyages', 'client'])->findOrFail($commandeId);
        return view('billets', compact('commande'));
    }
}
