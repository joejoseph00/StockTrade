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
            $table->decimal('high','12','12');
            $table->decimal('open','12','12');
            $table->decimal('close','12','12');
            $table->decimal('low','12','12');
            $table->decimal('volume','12','12');
            $table->decimal('unadjhigh','12','12');
            $table->decimal('unadjlow','12','12');
            $table->decimal('unadjopen','12','12');
            $table->decimal('unadjclose','12','12');
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
