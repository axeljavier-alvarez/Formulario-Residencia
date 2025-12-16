<?php

namespace Database\Seeders;
use App\Models\Tramite;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TramiteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
     public function run(): void
    {
        $tramites = [
            'Magisterio', 
            'Solicitar DPI al Registro Nacional de las Personas',
            'Inscripción extemporánea de un menor de edad ante el Registro Nacional de las Personas',
            'Inscripción extemporánea de un mayor de edad ante el Registro Nacional de las Personas',
            'Tramites legales en materia civil',
            'Tramites legales en materia penal'
        ];

        
        foreach($tramites as $nombre){
             $slugBase = Str::slug($nombre);
            $slug = $slugBase;
            $contador = 1;


            while(Tramite::where('slug', $slug)->exists()){
                $slug = $slugBase . '-' . $contador;
                 $contador++;
            }

            Tramite::create([
                'nombre' => $nombre,
                'slug' => $slug
            ]);
        }
    }

}
