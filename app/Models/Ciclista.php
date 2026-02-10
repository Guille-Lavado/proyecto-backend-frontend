<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\User;

class Ciclista extends Model
{
    protected $fillable = [
        'id_user',
        'apellido',
        "fecha_nacimiento",
        "peso_base",
        "altura_base",
    ];
    protected $dates = [
        'published_at',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'id_user');
    }
}
