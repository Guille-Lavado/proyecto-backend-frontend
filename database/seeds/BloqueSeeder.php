<?php

use Illuminate\Database\Seeder;
use App\Models\Bloques_entrenamiento;

class BloqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Bloques_entrenamiento::class, 3)->create();
    }
}
