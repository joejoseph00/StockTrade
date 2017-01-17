<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStocksTable extends Migration
{
    /**
    * Run the migrations.
    *
    * @return void
    */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->increments('id');
            $table->string('symbol',64);
            $table->string('name');
            $table->text('issuer')->nullable();
            $table->string('type');
            $table->text('recordts')->nullable();
            $table->text('statistics')->nullable();
            $table->text('profile')->nullable();

            $table->decimal('low','64','12')->nullable();
            $table->decimal('high','64','12')->nullable();
            $table->decimal('price','64','12')->nullable();
            $table->decimal('revenue','64','12')->nullable();
            $table->decimal('value','64','12')->nullable();

            $table->string('recommendation');
            $table->timestamps();
            $table->unique('symbol');
        });
    }

    /**
    * Reverse the migrations.
    *
    * @return void
    */
    public function down()
    {
        Schema::dropIfExists('stocks');
    }
}
