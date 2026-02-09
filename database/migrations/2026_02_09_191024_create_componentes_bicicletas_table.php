<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateComponentesBicicletasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('componentes_bicicletas', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('id_bicicleta');
            $table->foreign('id_bicicleta')
                ->references('id')
                ->on('bicicletas')
                ->onDelete('cascade');

            $table->unsignedBigInteger('id_tipo_componente');
            $table->foreign('id_tipo_componente')
                ->references('id')
                ->on('tipos_componente');

            $table->string('marca', 100)->nullable();
            $table->string('modelo', 100)->nullable();
            $table->text('especificacion')->nullable();
            $table->string('velocidad', 50)->nullable();
            $table->string('posicion', 50)->nullable();
            $table->date('fecha_montaje');
            $table->date('fecha_retiro')->nullable();
            $table->decimal('km_actuales', 8, 2)->default(0);
            $table->decimal('km_max_recomendado', 8, 2)->nullable();
            $table->boolean('activo')->default(true);
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
        Schema::dropIfExists('componentes_bicicletas');
    }
}
