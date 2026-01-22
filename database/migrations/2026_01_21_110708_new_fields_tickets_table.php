<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class NewFieldsTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->string('titulo')->after('id');
            $table->string('estado')->default('creado')->change();
            $table->dateTime('fecha_asignado')->nullable()->after('estado');
            $table->dateTime('fecha_corregido')->nullable()->after('fecha_asignado');
            $table->dateTime('fecha_cerrado')->nullable()->after('fecha_corregido');
            $table->string('nivel_sla')->nullable()->after('prioridad');
            $table->boolean('vit')->default(false)->after('nivel_sla');
            $table->unsignedBigInteger('categoria_id')->nullable()->after('vit');
            $table->unsignedBigInteger('subcategoria_id')->nullable()->after('categoria_id');

            $table->foreign('categoria_id')->references('id')->on('categories')->onDelete('set null');
            $table->foreign('subcategoria_id')->references('id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickets', function (Blueprint $table) {
            $table->dropColumn('titulo');
            $table->dropColumn('fecha_asignado');
            $table->dropColumn('fecha_corregido');
            $table->dropColumn('fecha_cerrado');
            $table->dropColumn('nivel_sla');
            $table->dropColumn('vit');
            $table->dropForeign(['categoria_id']);
            $table->dropColumn('categoria_id');
            $table->dropForeign(['subcategoria_id']);
            $table->dropColumn('subcategoria_id');
        });
    }
}
