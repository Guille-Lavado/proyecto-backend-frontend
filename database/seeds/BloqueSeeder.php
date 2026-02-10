<?php

use Illuminate\Database\Seeder;
use App\Models\BloqueEntrenamiento;

class BloqueSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(BloqueEntrenamiento::class, 3)->create();
    }
}
