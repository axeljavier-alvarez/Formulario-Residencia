<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class DetalleSolicitud extends Model
{
    use HasFactory;

    protected $table = 'detalle_solicitud';

    protected $fillable = [
        'path',
        'tipo',
        'solicitud_id',
        'user_id', 
        'requisito_tramite_id'
    ];

    // asignando relaciones
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class, 'solicitud_id');
    }

    public function requisitoTramite()
    {
        return $this->belongsTo(RequisitoTramite::class, 'requisito_tramite_id');
    }

    public function user(){
        return $this->belongsTo(User::class);
    }

     public function dependiente()
    {
        return $this->hasOne(Dependiente::class);
    }

}
