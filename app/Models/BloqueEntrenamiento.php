<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BloqueEntrenamiento extends Model
{
    protected $table = 'bloques_entrenamientos';
    protected $fillable = [
        'nombre',
        'descripcion',
        "tipo",
        "duracion_estimada",
        "potencia_pct_min",
        "potencia_pct_max",
        "pulso_pct_max",
        "pulso_reserva_pct",
        "comentario",
    ];
    protected $dates = [
        'published_at',
    ];
}
