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
        // Tabla ciclistas
        Schema::create('ciclistas', function (Blueprint $table) {
            $table->id();
            $table->string("apellido")->nullable();
            $table->timestamp("fecha_nacimiento")->nullable();
            $table->integer("peso_base")->nullable();
            $table->integer("altura_base")->nullable();
            $table->timestamps();

            // Crear clave foranea que se relaciona con la tabla users 
            $table->unsignedBigInteger('id_user');
            $table->foreign('id_user')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // Tabla bicicletas
        Schema::create('bicicletas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 100);
            $table->string('tipo', 50);
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
        Schema::dropIfExists('ciclistas');
        Schema::dropIfExists('bicicletas');
    }
}
