<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProyectosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('proyectos')) {
            Schema::table('proyectos', function (Blueprint $table) {
                if (!Schema::hasColumn('proyectos', 'nombre')) {
                    $table->string('nombre');   
                }
                if (!Schema::hasColumn('proyectos', 'descripcion')) {
                    $table->text('descripcion');   
                }
                if (!Schema::hasColumn('proyectos', 'estado')) {
                    $table->enum('estado', ['creado', 'finalizado', 'suspendido']);
                }
                if (!Schema::hasColumn('proyectos', 'progreso')) {
                    $table->float('progreso')->nullable();
                }
                if (!Schema::hasColumn('proyectos', 'cliente_id')) {
                    $table->unsignedBigInteger('cliente_id')->nullable();   
                    $table->foreign('cliente_id')->references('id')->on('clientes');
                }
                if (!Schema::hasColumn('proyectos', 'user_id')) {
                    $table->unsignedBigInteger('user_id');
                    $table->foreign('user_id')->references('id')->on('users');    
                }
            });
        } else {
            Schema::create('proyectos', function (Blueprint $table) {
                $table->id();
                $table->string('nombre');
                $table->text('descripcion');
                $table->float('progreso')->default(0)->min(0)->max(100);
                $table->enum('estado', ['iniciado', 'finalizado', 'suspendido'])->default('iniciado');
                $table->unsignedBigInteger('cliente_id');
                $table->unsignedBigInteger('user_id');
                $table->timestamps();
                $table->softDeletes();

                $table->foreign('cliente_id')->references('id')->on('clientes');
                $table->foreign('user_id')->references('id')->on('users');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('proyectos');
    }
}
