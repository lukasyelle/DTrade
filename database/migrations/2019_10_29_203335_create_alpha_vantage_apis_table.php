<?php

use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateAlphaVantageApisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('alpha_vantage_apis', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('api_key');
            $table->integer('user_id')->unsigned()->index();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });

        if ($user = User::first()) {
            DB::table('alpha_vantage_apis')->insert(
                ['user_id' => $user->id, 'api_key' => env('DEFAULT_AV_API_KEY')]
            );
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('alpha_vantage_apis');
    }
}
