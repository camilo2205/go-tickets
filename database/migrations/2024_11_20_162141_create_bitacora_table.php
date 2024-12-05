<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;

class CreateBitacoraTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('bitacora')) {
            Schema::table('bitacora', function (Blueprint $table) {
                if (!Schema::hasColumn('bitacora', 'funcionario_id')) {
                    $table->unsignedBigInteger('funcionario_id')->nullable();   
                    $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('set null');
                }
                if (!Schema::hasColumn('bitacora', 'user_id')) {
                    $table->unsignedBigInteger('user_id');
                    $table->foreign('user_id')->references('id')->on('users');    
                }
                if (!Schema::hasColumn('bitacora', 'proyecto')) {
                    $table->unsignedBigInteger('proyecto_id');
                    $table->foreign('proyecto_id')->references('id')->on('proyectos');    
                }
                if (!Schema::hasColumn('bitacora', 'descripcion')) {
                    $table->text('descripcion');   
                }
                if (!Schema::hasColumn('bitacora', 'esfuerzo')) {
                    $table->unsignedInteger('esfuerzo');   
                }
                if (!Schema::hasColumn('bitacora', 'inicio')) {
                    $table->dateTime('inicio');   
                }
                if (!Schema::hasColumn('bitacora', 'fin')) {
                    $table->dateTime('fin');   
                }
                if (!Schema::hasColumn('bitacora', 'created_at')) {
                    $table->timestamps();   
                }
                if (!Schema::hasColumn('bitacora', 'deleted_at')) {
                    $table->softDeletes();   
                }
                
            });
        } else {
            Schema::create('bitacora', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('funcionario_id')->nullable();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('proyecto_id');
                $table->text('descripcion');
                $table->unsignedInteger('esfuerzo');
                $table->dateTime('inicio');
                $table->dateTime('fin');
                $table->timestamps();
                $table->softDeletes();
                
                $table->foreign('funcionario_id')->references('id')->on('funcionarios')->onDelete('set null');
                $table->foreign('user_id')->references('id')->on('users');
                $table->foreign('proyecto_id')->references('id')->on('proyectos');
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
        Schema::dropIfExists('bitacora');
    }
}
