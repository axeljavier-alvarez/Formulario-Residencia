<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Dependiente extends Model
{
        use HasFactory;

        public $timestamps = false;

        protected $fillable = [
                'nombres',
                'apellidos',
                'detalle_solicitud_id'
        ];
        // public function solicitud()
        // {
        //     return $this->belongsTo(Solicitud::class, 'solicitud_id');
        // }
        
        public function detalleSolicitud()
        {
            return $this->belongsTo(
                DetalleSolicitud::class,
                'detalle_solicitud_id'
            );
        }

       

}
