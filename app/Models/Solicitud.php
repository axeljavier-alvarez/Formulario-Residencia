<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Solicitud extends Model
{

        use HasFactory;

    protected $table = 'solicitudes';

    // DATOS DE SOLICITUDES
    protected $fillable = [
        'no_solicitud',
        'anio',
        'nombre',
        'apellido',
        'email',
        'telefono',
        'cui',
        'domicilio',
        'observaciones'
    ];

    // una solicitud pertenece a una zona
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

}
