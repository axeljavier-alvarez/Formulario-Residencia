<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tramite extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'slug', 'path'];

    public $timestamps = false;


    public function requisitos()
    {
        return $this->belongsToMany(
            Requisito::class,
            'requisito_tramite',
            'tramite_id',
            'requisito_id'
    );
    }


     public function plantillas()
    {
        return $this->hasMany(Plantilla::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

}
