<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTickerHistoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ticker_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('ticker_id')->unsigned()->index();
            $table->foreign('ticker_id')->references('id')->on('tickers')->onDelete('cascade');
            $table->json('data');
            $table->timestamp('as_of');
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
        Schema::dropIfExists('ticker_histories');
    }
}
