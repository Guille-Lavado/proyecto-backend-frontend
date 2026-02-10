<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueEntrenamiento extends Model
{
    protected $table = 'bloques_entrenamiento';
    protected $fillable = ['nombre', 'descripcion', 'tipo', 'duracion_estimada', 'potencia_pct_min', 'potencia_pct_max', 'pulso_pct_max', 'pulso_reserva_pct', 'comentario'];

    public function sesiones() {
        return $this->belongsToMany(SesionEntrenamiento::class, 'sesion_bloques', 'id_bloque_entrenamiento', 'id_sesion_entrenamiento');
    }
}
