<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVistoToRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            if (!Schema::hasColumn('respuestas', 'visto')) {
                $table->boolean('visto')->default(false);
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
        Schema::table('respuestas', function (Blueprint $table) {
            if (Schema::hasColumn('respuestas', 'visto')) {
                $table->dropColumn('visto');
            }
        });
    }
}
