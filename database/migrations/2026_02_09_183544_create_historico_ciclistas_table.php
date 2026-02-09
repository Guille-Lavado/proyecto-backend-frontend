<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHistoricoCiclistasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('historico_ciclistas', function (Blueprint $table) {
            $table->id();
            
            // Crear clave foranea que se relaciona con la tabla ciclistas
            $table->unsignedBigInteger('id_ciclista');
            $table->foreign('id_ciclista')
                ->references('id')
                ->on('ciclistas')
                ->onDelete('cascade');

            $table->date('fecha');
            $table->decimal('peso', 5, 2);
            $table->integer('ftp')->nullable();
            $table->integer('pulso_max')->nullable();
            $table->integer('pulso_reposo')->nullable();
            $table->integer('potencia_max')->nullable();
            $table->decimal('grasa_corporal', 4, 2)->nullable();
            $table->integer('vo2max')->nullable();
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
        Schema::dropIfExists('historico_ciclistas');
    }
}
