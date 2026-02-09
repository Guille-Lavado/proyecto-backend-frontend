<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Ciclista;
use Faker\Generator as Faker;
use Illuminate\Support\Facades\Hash;

$factory->define(Ciclista::class, function (Faker $faker) {
    return [
        'nombre' => $faker->firstName(),
        'apellido' => $faker->lastName(),
        "peso_base" => $faker->numberBetween(70, 100),
        "altura_base" => $faker->numberBetween(170, 200),
        'email' => $faker->unique()->safeEmail(),
        "password" => Hash::make('password')
    ];
});
