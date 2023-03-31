<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateServersTable extends Migration
{
    public function up()
    {
        Schema::create('servers', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->bigInteger('disk_capacidad');
            $table->string('ram');
            $table->string('nit');
            $table->unsignedBigInteger('cliente_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('cliente_id')->references('id')->on('clientes');
        });
    }

    public function down()
    {
        Schema::dropIfExists('servers');
    }
}