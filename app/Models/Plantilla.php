<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plantilla extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['tipo', 'path', 'tramite_id'];


    public function tramite(){
        return $this->belongsTo(Tramite::class);
    }
}
