<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;


class CreateDisksTable extends Migration
{
    public function up()
    {
        Schema::create('disks', function (Blueprint $table) {
            $table->id();
            $table->integer('capacity');
            $table->integer('used');
            $table->text('mounted');
            $table->unsignedBigInteger('server_id')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('server_id')->references('id')->on('servers');
          
        });
    }

    public function down()
    {
        Schema::dropIfExists('disks');
    }
}