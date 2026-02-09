<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Plan_entrenamiento extends Model
{
    protected $fillable = [
        'id_ciclista',
        'nombre',
        'descripcion',
        "fecha_inicio",
        "fecha_fin",
        "objetivo",
        "activo",
    ];
    protected $dates = [
        'published_at',
    ];

    public function ciclista() {
        return $this->belongsTo(Ciclista::classs, "id_ciclista");
    }
}
