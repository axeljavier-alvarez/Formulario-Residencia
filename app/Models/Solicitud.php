<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\requisitosTramites;

class Solicitud extends Model
{

        use HasFactory;

    protected $table = 'solicitudes';

    public $observacion_bitacora;
    

    // DATOS DE SOLICITUDES
    protected $fillable = [
        'no_solicitud',
        'anio',
        'nombres',
        'apellidos',
        'email',
        'telefono',
        'cui',
        'domicilio',
        'observaciones',
        'zona_id',
        'estado_id',
        'razon',
        'tramite_id'
    ];

    // una solicitud pertenece a una zona
    public function zona()
    {
        return $this->belongsTo(Zona::class, 'zona_id');
    }

    public function estado()
    {
        return $this->belongsTo(Estado::class, 'estado_id');
    }

    protected function builder()
{
    return Solicitud::query()->with('estado');
}


    // muchos a muchos con requisitostramites
    public function requisitosTramites()
    {
        return $this->belongsToMany(
            RequisitoTramite::class, 
            'solicitudes_has_requisitos_tramites', 
            'solicitud_id', 
            'requisito_tramite_id'
        );
    }

    // relacion con detalle solicitud
    public function detalles()
    {
        return $this->hasMany(
            DetalleSolicitud::class, 'solicitud_id'
        );
    }

//    public function dependientes(){
//     return $this->hasMany(Dependiente::class, 'solicitud_id');
//    }


   public function bitacoras()
   {
    return $this->hasMany(Bitacora::class);
   }

   public function tramite()
   {
    return $this->belongsTo(Tramite::class);
   }
   

}
