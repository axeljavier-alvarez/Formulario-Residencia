<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SolicitudController;

Route::get('/', function () {
    return view('welcome');
});


// IR A LA RUTA DE FORMULARIO DE SOLICITUD
// Route::get('/solicitudes', function(){
//     return view('solicitudes.create');
// })->name('solicitudes.create');



// Cursos
Route::get('solicitud', [SolicitudController::class, 'create'])
    // ->middleware(CheckCartItems::class)
    ->name('solicitudes.create');

// Solicitudes
Route::get('consulta', [SolicitudController::class, 'consultarSolicitudes'])
->name('solicitudes.publica');

// Route::post('solicitud', [SolicitudController::class, 'store'])
// ->name('solicitudes.store');


// VER LAS CONSTANCIAS CREADAS
/* Route::get('ver-consultas', function(){
    return view ('consulta.index');
})->name('consulta.index'); */




Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');
});
