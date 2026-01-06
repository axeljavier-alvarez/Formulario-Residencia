<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Interno\SolicitudController;


Route::get('ver-consultas', function(){
    return view ('interno.index');
})->name('consulta.index');


Route::resource('solicitudes', SolicitudController::class);