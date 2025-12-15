<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RequisitoTramite extends Model
{
        use HasFactory;
        protected $table = 'requisito_tramite';
        protected $fillable = [
            'requisito_id',
            'tramite_id'
        ];

        // relacion con requisito
        public function requisito()
        {
            return $this->belongsTo(Requisito::class, 'requisito_id');
        }
        // muchos a muchos con solicitudes
        public function solicitudes()
        {
            return $this->belongsToMany(
                Solicitud::class,
                'solicitudes_has_requisitos_tramites',
                'requisito_tramite_id',
                'solicitud_id'
            );
        }

        // relacion con detalle solicitud
        public function detalles()
        {
            return $this->hasMany(DetalleSolicitud::class, 'requisito_tramite_id');
        }

}
