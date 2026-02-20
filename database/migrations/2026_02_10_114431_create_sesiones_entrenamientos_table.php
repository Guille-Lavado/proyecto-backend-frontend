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
        // 8. Tabla: sesiones_entrenamientos
        Schema::create('sesiones_entrenamientos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_plan')
                ->nullable()
                ->constrained('plan_entrenamientos')
                ->onDelete("set null");

            $table->dateTime('fecha');
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
