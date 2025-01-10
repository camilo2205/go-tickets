<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFechaVencimientoMeddreamToCliente extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (!Schema::hasColumn('clientes', 'notifated_meddream')) {
                $table->boolean('notifated_meddream')->default(false);	
            }

            if (!Schema::hasColumn('clientes', 'fecha_vencimiento_meddream')) {
                $table->dateTime('fecha_vencimiento_meddream')->nullable();	
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clientes', function (Blueprint $table) {
            if (Schema::hasColumn('clientes', 'notifated_meddream')) {
                $table->dropColumn('notifated_meddream');
            }

            if (Schema::hasColumn('clientes', 'fecha_vencimiento_meddream')) {
                $table->dropColumn('fecha_vencimiento_meddream');
            }
        });
    }
}
