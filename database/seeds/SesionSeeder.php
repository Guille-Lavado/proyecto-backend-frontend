<?php

use Illuminate\Database\Seeder;
use App\Models\SesionEntrenamiento;

class SesionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(SesionEntrenamiento::class, 5)->create();
    }
}
