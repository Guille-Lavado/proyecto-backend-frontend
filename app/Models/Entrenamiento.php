<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Entrenamiento extends Model
{
    protected $table = 'entrenamientos';
    protected $fillable = [
        'id_ciclista', 'id_bicicleta', 'id_sesion', 'fecha', 'duracion', 'kilometros', 
        'recorrido', 'pulso_medio', 'pulso_max', 'potencia_media', 'potencia_normalizada', 
        'velocidad_media', 'puntos_estres_tss', 'factor_intensidad_if', 'ascenso_metros', 'comentario'
    ];

    public function ciclista() {
        return $this->belongsTo(Ciclista::class, 'id_ciclista');
    }

    public function bicicleta() {
        return $this->belongsTo(Bicicleta::class, 'id_bicicleta');
    }

    public function sesionOriginal() {
        return $this->belongsTo(SesionEntrenamiento::class, 'id_sesion');
    }
}
