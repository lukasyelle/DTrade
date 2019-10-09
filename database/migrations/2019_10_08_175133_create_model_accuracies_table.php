<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateModelAccuraciesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('model_accuracies', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('stock_id')->unsigned()->index();
            $table->foreign('stock_id')->references('id')->on('stocks')->onDelete('cascade');
            $table->string('time_period');
            $table->integer('duration');
            $table->float('accuracy_large_loss');
            $table->float('accuracy_moderate_loss');
            $table->float('accuracy_small_loss');
            $table->float('accuracy_large_profit');
            $table->float('accuracy_moderate_profit');
            $table->float('accuracy_small_profit');
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
        Schema::dropIfExists('model_accuracies');
    }
}
