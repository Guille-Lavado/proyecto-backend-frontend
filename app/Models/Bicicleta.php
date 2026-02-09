<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Bicicleta extends Model
{
    protected $fillable = [
        'nombre',
        'tipo',
        'comentario'
    ];

    public function componentes() {
        return $this->hasMany(Componente_bicicleta::class, 'id_bicicleta');
    }
}
