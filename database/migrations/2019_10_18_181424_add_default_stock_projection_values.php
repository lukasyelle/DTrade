<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultStockProjectionValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('stock_projections', function (Blueprint $table) {
            $table->float('probability_large_loss')->default(0.0)->change();
            $table->float('probability_moderate_loss')->default(0.0)->change();
            $table->float('probability_small_loss')->default(0.0)->change();
            $table->float('probability_large_profit')->default(0.0)->change();
            $table->float('probability_moderate_profit')->default(0.0)->change();
            $table->float('probability_small_profit')->default(0.0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('stock_projections', function (Blueprint $table) {
            $table->float('probability_large_loss')->default(null)->change();
            $table->float('probability_moderate_loss')->default(null)->change();
            $table->float('probability_small_loss')->default(null)->change();
            $table->float('probability_large_profit')->default(null)->change();
            $table->float('probability_moderate_profit')->default(null)->change();
            $table->float('probability_small_profit')->default(null)->change();
        });
    }
}
