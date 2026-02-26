<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plantilla;
use App\Models\Tramite;

class PlantillaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $magisterio = Tramite::where('slug', 'magisterio')->first();

        // caso especial magisterio
        if($magisterio){
            Plantilla::create([
                'tramite_id' => $magisterio->id,
                'tipo' => 'con_carga',
                'path' => 'pdf/magisterio_con_cargas.pdf'
            ]);

            Plantilla::create([
                'tramite_id' => $magisterio->id,
                'tipo' => 'sin_carga',
                'path' => 'pdf/magisterio_sin_cargas.pdf'
            ]);
        }

        // otros tramites
        $tramitesGenerales = Tramite::where('slug', '!=', 'magisterio')
        ->get();

        foreach($tramitesGenerales as $tramite){
            Plantilla::create([                
                'tramite_id' => $tramite->id,
                'tipo' => 'general',
                'path' => 'pdf/solicitudes_varias.pdf'
            ]);
        }


        // dd(Tramite::count());
    }
}
