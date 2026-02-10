<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SesionEntrenamiento extends Model
{
    protected $table = 'sesiones_entrenamiento';
    protected $fillable = ['id_plan', 'fecha', 'nombre', 'descripcion', 'completada'];

    public function plan() {
        return $this->belongsTo(PlanEntrenamiento::class, 'id_plan');
    }

    // Relación Muchos a Muchos con Bloques a través de la tabla pivote
    public function bloques() {
        return $this->belongsToMany(BloqueEntrenamiento::class, 'sesion_bloques', 'id_sesion_entrenamiento', 'id_bloque_entrenamiento')
                    ->withPivot('orden', 'repeticiones', 'duracion_real', 'potencia_real', 'pulso_real')
                    ->withTimestamps();
    }
}