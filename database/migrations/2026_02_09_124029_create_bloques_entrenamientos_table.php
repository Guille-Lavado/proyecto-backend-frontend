<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBloquesEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bloques_entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->text('descripcion')->nullable();
            $table->string('tipo', 50);
            $table->integer('duracion_estimada');
            $table->integer('potencia_pct_min')->nullable();
            $table->integer('potencia_pct_max')->nullable();
            $table->integer('pulso_pct_max')->nullable();
            $table->integer('pulso_reserva_pct')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bloques_entrenamientos');
    }
}
