<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\SesionEntrenamiento;
use Faker\Generator as Faker;

$factory->define(SesionEntrenamiento::class, function (Faker $faker) {
    return [
        "id_plan" => 1,
        "fecha" => now(),
        "nombre" => $faker->text(15),
        "completada" => false,
        /*$id_sesion = DB::table('sesiones_entrenamientos')->insertGetId([
            'id_plan' => $id_plan,
            'fecha' => now(),
            'nombre' => 'Entreno día 1',
            'completada' => true,
        ]);*/
    ];
});
