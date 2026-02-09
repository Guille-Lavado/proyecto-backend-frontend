<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePlanEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('plan_entrenamientos', function (Blueprint $table) {
            $table->id();
            $table->string("nombre");
            $table->text("descripcion");
            $table->date("fecha_inicio");
            $table->date("fecha_fin");
            $table->text("objetivo");
            $table->boolean("activo")->default(false);
            $table->timestamps();

            // Crear clave foranea que se relaciona con la tabla ciclistas 
            $table->unsignedBigInteger('ciclista_id');
            $table->foreign('ciclista_id')
                ->references('id')
                ->on('ciclistas')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_entrenamientos');
    }
}
