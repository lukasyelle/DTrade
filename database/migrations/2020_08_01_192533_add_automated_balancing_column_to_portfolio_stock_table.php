<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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
