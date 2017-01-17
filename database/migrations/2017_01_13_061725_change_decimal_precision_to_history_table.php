<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDecimalPrecisionToHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('history', function (Blueprint $table) {
            //
            $table->decimal('high','64','12')->change();
            $table->decimal('open','64','12')->change();
            $table->decimal('close','64','12')->change();
            $table->decimal('low','64','12')->change();
            $table->decimal('volume','64','12')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('history', function (Blueprint $table) {
            //
        });
    }
}
