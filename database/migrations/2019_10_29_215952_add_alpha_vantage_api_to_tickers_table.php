<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAlphaVantageApiToTickersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tickers', function (Blueprint $table) {
            $table->integer('alpha_vantage_api_id')->unsigned()->index()->default(1);
            $table->foreign('alpha_vantage_api_id')->references('id')->on('alpha_vantage_apis')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tickers', function (Blueprint $table) {
            $table->dropColumn('alpha_vantage_api_id');
        });
    }
}
