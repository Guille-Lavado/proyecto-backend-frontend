<?php

use Illuminate\Database\Seeder;
use App\Models\Ciclista;

class CiclistaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Ciclista::class, 10)->create();
    }
}
