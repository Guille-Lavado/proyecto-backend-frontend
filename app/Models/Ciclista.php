<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ciclista extends Model
{
    protected $fillable = [
        'nombre',
        'apellido',
        "fecha_nacimiento",
        "peso_base",
        "altura_base",
        "email",
        "password",
        'published_at',
    ];
    protected $dates = [
        'published_at',
    ];

    public function plan_entrenamiento() {
        return $this->hasMany(Plan_entrenamiento::class, "id_ciclista");
    }
}
