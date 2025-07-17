<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTareasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tareas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('encargado_id')->nullable();
            $table->unsignedBigInteger('cliente_id');
            $table->string('nombre');
            $table->text('descripcion');
            $table->enum('prioridad', ['baja', 'media', 'alta'])->nullable();
            $table->unsignedInteger('enfoque')->nullable();
            $table->enum('estado', ['pendiente', 'en_progreso', 'completada', 'cancelada'])->default('pendiente');

            $table->foreign('encargado_id')->references('id')->on('funcionarios');
            $table->foreign('cliente_id')->references('id')->on('clientes');
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
        Schema::dropIfExists('tareas');
    }
}
