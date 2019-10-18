<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDefaultAccuracyValues extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('model_accuracies', function (Blueprint $table) {
            $table->float('accuracy_large_loss')->default(0.0)->change();
            $table->float('accuracy_moderate_loss')->default(0.0)->change();
            $table->float('accuracy_small_loss')->default(0.0)->change();
            $table->float('accuracy_large_profit')->default(0.0)->change();
            $table->float('accuracy_moderate_profit')->default(0.0)->change();
            $table->float('accuracy_small_profit')->default(0.0)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('model_accuracies', function (Blueprint $table) {
            $table->float('accuracy_large_loss')->default(null)->change();
            $table->float('accuracy_moderate_loss')->default(null)->change();
            $table->float('accuracy_small_loss')->default(null)->change();
            $table->float('accuracy_large_profit')->default(null)->change();
            $table->float('accuracy_moderate_profit')->default(null)->change();
            $table->float('accuracy_small_profit')->default(null)->change();
        });
    }
}
