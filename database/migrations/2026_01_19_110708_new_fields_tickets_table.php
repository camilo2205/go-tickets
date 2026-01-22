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
            $table->string('categoria')->nullable()->after('nivel_sla');
            $table->string('subcategoria')->nullable()->after('categoria');
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
            $table->dropColumn('fecha_asignado');
            $table->dropColumn('fecha_corregido');
            $table->dropColumn('fecha_cerrado');
            $table->dropColumn('nivel_sla');
        });
    }
}
