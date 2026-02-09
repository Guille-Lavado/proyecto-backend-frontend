<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Bloques_entrenamiento;
use Faker\Generator as Faker;

$factory->define(Bloques_entrenamiento::class, function (Faker $faker) {
    return [
        "nombre" => $faker->firstName(),
        'descripcion' => $faker->text(100),
        'tipo' => $faker->randomElement(['Calentamiento', 'Series', 'Recuperación', 'Sprint']),
        'duracion_estimada' => $faker->numberBetween(5, 60),
        'potencia_pct_min' => $faker->numberBetween(50, 70),
        'potencia_pct_max' => $faker->numberBetween(80, 100),
        'pulso_pct_max' => $faker->numberBetween(160, 190),
        'pulso_reserva_pct' => $faker->numberBetween(60, 85),
        'comentario' => $faker->text(100),
    ];
});
