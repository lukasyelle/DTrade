<?php

namespace App\Http\Controllers;

use App\Charts\TestChart;
use App\Stock;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $chart = new TestChart(Stock::inRandomOrder()->first());
        return view('home', [
            'portfolios' => Auth::user()->portfolios(),
            'chart' => $chart
        ]);
    }
}
