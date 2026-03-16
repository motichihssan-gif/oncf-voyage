<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billet extends Model
{
    protected $fillable = [
        'id_voyage',
        'id_commande',
        'qte',
        'nom_voyageur',
        'passport_voyageur',
    ];
}
