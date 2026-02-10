<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(CiclistaSeeder::class);
        $this->call(BloqueSeeder::class);

        DB::table('plan_entrenamientos')->insert([
            'id_ciclista' => 1,
            'nombre' => 'Preparación Quebrantahuesos',
            "descripcion" => "Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.",
            'fecha_inicio' => now()->startOfMonth(),
            'fecha_fin' => now()->addMonths(3),
            'objetivo' => 'Mejorar resistencia aeróbica',
            'activo' => true,
        ]);
    }
}
