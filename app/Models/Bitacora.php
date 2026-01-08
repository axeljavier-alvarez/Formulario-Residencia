<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bitacora extends Model
{
    use HasFactory;

    // asignacion masiva
    protected $fillable = [
        'solicitud_id',
        'user_id',
        'evento',
        'descripcion'
    ];

    // eloquent con solicitud
    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    // eloquent con user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
