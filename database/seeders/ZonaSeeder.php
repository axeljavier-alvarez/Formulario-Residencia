<?php

namespace Database\Seeders;

use App\Models\Zona;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ZonaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        
        // $zonas = ['Zona 1', 'Zona 2', 'Zona 3'];

        // foreach($zonas as $nombre){

        //     Zona::create(['nombre' => $nombre]);
        // }

        for($i = 1; $i <= 25; $i++){
            Zona::create([
                'nombre' => 'Zona ' . $i
            ]);
        }
    }
}
