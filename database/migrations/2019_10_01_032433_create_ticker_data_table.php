<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTickerDataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticker_data', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ticker_id')->unsigned()->index();
            $table->foreign('ticker_id')->references('id')->on('tickers')->onDelete('cascade');
            $table->float('open');
            $table->float('high');
            $table->float('low');
            $table->float('close');
            $table->float('previous_close');
            $table->float('change');
            $table->float('change_percent');
            $table->integer('volume');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ticker_data');
    }
}
