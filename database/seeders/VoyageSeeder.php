<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Voyage;

class VoyageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $voyages = [
            [
                'code_voyage' => 'ALBORAQ-50',
                'heureDepart' => '07:00:00',
                'villeDepart' => 'Tanger',
                'heureDarrivee' => '08:20:00',
                'villeDarrivee' => 'Kenitra',
                'prixVoyage' => 120.00,
                'prixPromo' => 99.00,
            ],
            [
                'code_voyage' => 'ALBORAQ-51',
                'heureDepart' => '08:00:00',
                'villeDepart' => 'Rabat',
                'heureDarrivee' => '09:30:00',
                'villeDarrivee' => 'Casablanca',
                'prixVoyage' => 80.00,
            ],
            [
                'code_voyage' => 'ATLAS-102',
                'heureDepart' => '10:00:00',
                'villeDepart' => 'Casablanca',
                'heureDarrivee' => '13:45:00',
                'villeDarrivee' => 'Marrakech',
                'prixVoyage' => 110.00,
                'prixPromo' => 85.00,
            ],
            [
                'code_voyage' => 'ATLAS-205',
                'heureDepart' => '09:15:00',
                'villeDepart' => 'Fès',
                'heureDarrivee' => '12:30:00',
                'villeDarrivee' => 'Rabat',
                'prixVoyage' => 95.00,
            ],
            [
                'code_voyage' => 'ORIENT-301',
                'heureDepart' => '21:00:00',
                'villeDepart' => 'Oujda',
                'heureDarrivee' => '06:00:00',
                'villeDarrivee' => 'Casablanca',
                'prixVoyage' => 250.00,
            ],
            [
                'code_voyage' => 'ALBORAQ-55',
                'heureDepart' => '14:00:00',
                'villeDepart' => 'Tanger',
                'heureDarrivee' => '16:10:00',
                'villeDarrivee' => 'Casablanca',
                'prixVoyage' => 190.00,
                'prixPromo' => 150.00,
            ],
            [
                'code_voyage' => 'ATLAS-401',
                'heureDepart' => '07:30:00',
                'villeDepart' => 'Nador',
                'heureDarrivee' => '13:00:00',
                'villeDarrivee' => 'Fès',
                'prixVoyage' => 135.00,
            ],
            [
                'code_voyage' => 'ATLAS-402',
                'heureDepart' => '14:20:00',
                'villeDepart' => 'Taza',
                'heureDarrivee' => '16:45:00',
                'villeDarrivee' => 'Fès',
                'prixVoyage' => 45.00,
            ],
            [
                'code_voyage' => 'TNCR-601',
                'heureDepart' => '08:15:00',
                'villeDepart' => 'Casablanca',
                'heureDarrivee' => '09:45:00',
                'villeDarrivee' => 'El Jadida',
                'prixVoyage' => 37.00,
            ],
            [
                'code_voyage' => 'ATLAS-702',
                'heureDepart' => '11:00:00',
                'villeDepart' => 'Settat',
                'heureDarrivee' => '12:15:00',
                'villeDarrivee' => 'Casablanca',
                'prixVoyage' => 25.00,
            ],
            [
                'code_voyage' => 'TNCR-801',
                'heureDepart' => '17:30:00',
                'villeDepart' => 'Benguérir',
                'heureDarrivee' => '18:45:00',
                'villeDarrivee' => 'Safi',
                'prixVoyage' => 40.00,
            ],
        ];

        foreach ($voyages as $v) {
            Voyage::create($v);
        }
    }
}
