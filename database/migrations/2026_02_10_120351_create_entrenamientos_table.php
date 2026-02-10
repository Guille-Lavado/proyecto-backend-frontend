<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEntrenamientosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 9. Tabla: entrenamientos (La actividad ya realizada)
        Schema::create('entrenamientos', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_ciclista');
            $table->foreign('id_ciclista')->references('id')->on('ciclistas')->onDelete('cascade');

            $table->unsignedBigInteger('id_bicicleta')->nullable();
            $table->foreign('id_bicicleta')->references('id')->on('bicicletas')->nullOnDelete();

            $table->unsignedBigInteger('id_sesion')->nullable();
            $table->foreign('id_sesion')->references('id')->on('sesiones_entrenamientos')->nullOnDelete();

            $table->dateTime('fecha');
            $table->integer('duracion');
            $table->decimal('kilometros', 6, 2);
            $table->text('recorrido')->nullable();
            $table->integer('pulso_medio')->nullable();
            $table->integer('pulso_max')->nullable();
            $table->integer('potencia_media')->nullable();
            $table->integer('potencia_normalizada')->nullable();
            $table->decimal('velocidad_media', 5, 2)->nullable();
            $table->integer('puntos_estres_tss')->nullable();
            $table->decimal('factor_intensidad_if', 4, 3)->nullable();
            $table->integer('ascenso_metros')->nullable();
            $table->text('comentario')->nullable();
            $table->timestamps();
        });

        // 10. Tabla: sesion_bloques (Tabla pivote con atributos extra)
        Schema::create('sesion_bloques', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_sesion_entrenamiento');
            $table->foreign('id_sesion_entrenamiento')->references('id')->on('sesiones_entrenamientos')->onDelete('cascade');

            $table->unsignedBigInteger('id_bloque_entrenamiento');
            $table->foreign('id_bloque_entrenamiento')->references('id')->on('bloques_entrenamientos')->onDelete('restrict');

            $table->integer('orden');
            $table->integer('repeticiones')->default(1);
            
            $table->integer('duracion_real')->nullable();
            $table->integer('potencia_real')->nullable();
            $table->integer('pulso_real')->nullable();
            
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
        Schema::dropIfExists('entrenamientos');
        Schema::dropIfExists('sesion_bloques');
    }
}
