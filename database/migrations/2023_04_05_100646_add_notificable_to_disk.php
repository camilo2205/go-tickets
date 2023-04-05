<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNotificableToDisk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('disks', function (Blueprint $table) {
            if (!Schema::hasColumn('disks', 'notificable')) {
                $table->boolean('notificable')->default(false);
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
        Schema::table('disks', function (Blueprint $table) {
            if (Schema::hasColumn('disks', 'notificable')) {
                $table->dropColumn('notificable');
            }
        });
    }
}
