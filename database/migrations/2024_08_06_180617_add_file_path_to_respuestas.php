<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFilePathToRespuestas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('respuestas', function (Blueprint $table) {
            if (!Schema::hasColumn('respuestas', 'file_path')) {
                $table->string('file_path')->nullable();
                
            }
            if (!Schema::hasColumn('respuestas', 'file_name')) {
                $table->string('file_name')->nullable();
                
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
            if (Schema::hasColumn('respuestas', 'file_path')) {
                $table->dropColumn('file_path');
            }
            if (Schema::hasColumn('respuestas', 'file_name')) {
                $table->dropColumn('file_name');
            }
        });
    }
}
