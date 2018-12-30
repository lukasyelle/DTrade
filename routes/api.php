<?php

use Illuminate\Http\Request;
use RadicalLoop\Eod\Config as EodConfig;
use RadicalLoop\Eod\Api\Stock as Market;
use App\Jobs\Robinhood\JobTest;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) { return $request->user(); });

Route::get('stock/{ticker}/price', function (Request $request, $ticker) {
    $market = new Market(new EodConfig(env('EOD_API_KEY')));
    $stock = $market->realTime("$ticker.US");
    return $stock->json();
});

Route::group(['middleware'=>'auth:api','prefix'=>'process/'], function(){

    Route::get('test', function(Request $request){

        JobTest::dispatch(auth('api')->user());

    });

});