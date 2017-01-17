<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoryTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('history', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol');
            $table->decimal('high','64','12');
            $table->decimal('open','64','12');
            $table->decimal('close','64','12');
            $table->decimal('low','64','12');
            $table->decimal('volume','64','12');
            $table->timestamp('timestamp');
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('history');
    }
}
