<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(CiclistaSeeder::class);
        $id_user = DB::table('users')->insertGetId([
            "name" => "guille",
            "email" => "lavadoguille@gmail.com",
            "email_verified_at" => now(),
            "password" => Hash::make('12345678'),
        ]);
        $id_ciclista = DB::table('ciclistas')->insertGetId([
            "id_user" => $id_user,
        ]);

        $id_bici = DB::table('bicicletas')->insertGetId([
            'nombre' => 'Specialized Tarmac SL7',
            'tipo' => 'Carretera',
            'comentario' => 'Bici principal de competición',
            'created_at' => now(),
        ]);

        $this->call(BloqueSeeder::class);

        $id_plan = DB::table('plan_entrenamientos')->insertGetId([
            'id_ciclista' => $id_ciclista,
            'nombre' => 'Preparación Quebrantahuesos',
            "descripcion" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            'fecha_inicio' => now()->startOfMonth(),
            'fecha_fin' => now()->addMonths(3),
            'objetivo' => 'Mejorar resistencia aeróbica',
            'activo' => true,
        ]);

        $id_sesion = DB::table('sesiones_entrenamientos')->insertGetId([
            'id_plan' => $id_plan,
            'fecha' => now(),
            'nombre' => 'Entreno día 1',
            'completada' => true,
        ]);

        DB::table('entrenamientos')->insert([
            'id_ciclista' => $id_ciclista,
            'id_bicicleta' => $id_bici,
            'id_sesion' => $id_sesion,
            'fecha' => now(),
            'duracion' => rand(3600, 7200),
            'kilometros' => rand(30, 60),
            'pulso_medio' => rand(130, 150),
            'potencia_media' => rand(180, 230),
            'puntos_estres_tss' => rand(50, 120),
            'factor_intensidad_if' => rand(70, 90) / 100,
            'ascenso_metros' => rand(200, 1000),
        ]);
    }
}
