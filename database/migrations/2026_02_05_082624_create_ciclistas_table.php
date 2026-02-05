<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCiclistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ciclistas', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->string("apellido");
            $table->timestamp("fecha_nacimiento")->nullable();
            $table->timestamps();
            $table->integer("peso_base");
            $table->integer("altura_base");
            $table->string("email");
            $table->string("password");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ciclistas');
    }
}
