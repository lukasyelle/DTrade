<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAutomatedBalancingColumnToPortfolioStockTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('portfolio_stock', function (Blueprint $table) {
            $table->boolean('automated_balancing')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('portfolio_stock', function (Blueprint $table) {
            $table->dropColumn('automated_balancing');
        });
    }
}
