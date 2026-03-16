<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Voyage;

class VoyageController extends Controller
{
    public function formRecherche()
    {
        $villesDepart = Voyage::distinct()->pluck('villeDepart');
        $villesArrivee = Voyage::distinct()->pluck('villeDarrivee');
        $promos = Voyage::whereNotNull('prixPromo')->take(3)->get();
        return view('rechercher', compact('villesDepart', 'villesArrivee', 'promos'));
    }

    public function resultatRecherche(Request $request)
    {
        $vd = $request->ville_depart;
        $va = $request->ville_arrivee;
        $villesDepart = Voyage::distinct()->pluck('villeDepart');
        $villesArrivee = Voyage::distinct()->pluck('villeDarrivee');
        $voyages = Voyage::where('villeDepart', $vd)
            ->where('villeDarrivee', $va)
            ->get();
        return view('rechercher', compact('voyages', 'villesDepart', 'villesArrivee', 'vd', 'va'));
    }
}
