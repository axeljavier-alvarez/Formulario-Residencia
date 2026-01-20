<?php

use App\Http\Controllers\Interno\AnalisisController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Interno\SolicitudController;
use App\Http\Controllers\Interno\VisitaCampoController;
use App\Http\Controllers\Interno\DashboardController;
use App\Http\Controllers\Interno\AutorizacionesController;
use App\Http\Controllers\Interno\EmisionConstanciasController;

// Route::get('ver-consultas', function(){
//     return view ('interno.index');
// })->name('consulta.index');

Route::resource('dashboard', DashboardController::class);
Route::resource('solicitudes', SolicitudController::class);
Route::resource('analisis', AnalisisController::class);
Route::resource('visita-campo', VisitaCampoController::class);
Route::resource('autorizaciones', AutorizacionesController::class);
Route::resource('emision-constancia', EmisionConstanciasController::class);

Route::post('visita-campo/upload', [VisitaCampoController::class, 'upload'])
     ->name('visita-campo.upload');
