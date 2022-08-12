<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('cliente_id');
            $table->unsignedBigInteger('funcionario_id')->nullable();
            $table->unsignedBigInteger('cerrado_por')->nullable();
            $table->text('descripcion');
            $table->enum('prioridad', ['urgente', 'normal']);
            $table->enum('tipo', ['soporte', 'ajuste', 'desarrollo', 'capacitacion']);
            $table->enum('estado', ['creado', 'asignado', 'atendido', 'resuelto'])->default('creado');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cliente_id')->references('id')->on('clientes');
            $table->foreign('funcionario_id')->references('id')->on('funcionarios');
            $table->foreign('cerrado_por')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tickets');
    }
}
