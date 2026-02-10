<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSesionesEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 8. Tabla: sesiones_entrenamiento
        Schema::create('sesiones_entrenamiento', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_plan');
            $table->foreign('id_plan')->references('id')->on('planes_entrenamiento')->onDelete('cascade');

            $table->dateTime('fecha'); // DateTime es mejor para agendar hora exacta
            $table->string('nombre', 150);
            $table->text('descripcion')->nullable();
            $table->boolean('completada')->default(false);
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
        Schema::dropIfExists('sesiones_entrenamientos');
    }
}
