<?php

namespace App\Http\Controllers;
use App\Models\Solicitud;
use Illuminate\Http\Request;

class SolicitudController extends Controller
{


    

    // CREAR SOLICITUD USUARI9O
     public function create()
    {
        return view('solicitudes.create');
    }

    // consultar solicitud
    public function consultarSolicitudes()
    {
        return view('solicitudes.publica');
    }

    // public function store(Request $request)
    // {
    //     $data = $request->validate([
    //         'nombre' => 'required|string|max:60',
    //         'apellido' => 'required|string|max:60',
    //         'email' => 'required|email|max:45',
    //         'telefono'=> 'required|string|max:20',
    //         'cui'=>'required|string|size:13',
    //         'domicilio'=>'required|string|max:255',
    //         'observaciones' => 'nullable|string|max:255'

    //     ]);

    //     // poner año actual por defecto

    //     $data['anio'] = now()->year;

    //     // solicitud sin no_solicitud
    //     $solicitud = Solicitud::create($data);
    //     // Solicitud::create($data);

    //     $solicitud->no_solicitud = $solicitud->id . '-' . $solicitud->anio;
    //     $solicitud->save();

    //     return redirect()->back()->with('success', 'Solicitud enviada correctamente');
    // }





}
