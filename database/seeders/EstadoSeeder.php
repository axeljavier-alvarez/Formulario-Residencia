<?php

namespace Database\Seeders;
use App\Models\Estado;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class EstadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

           // DB::table('estados')->truncate();


    $estados = [
    'Pendiente',               
    'Visita asignada',    
    'Visita realizada',  
    'Por autorizar',
    'Por emitir',
    'Completado',         
    'Cancelado',
    'Previo'
    ];


    foreach($estados as $nombre){

        Estado::firstOrCreate(['nombre' => $nombre]);
    }

    }
}
