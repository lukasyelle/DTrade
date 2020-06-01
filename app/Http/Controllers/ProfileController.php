<?php

namespace App\Http\Controllers;

class ProfileController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        return view('profile.index');
    }

    public function robinhood()
    {
        return view('profile.robinhood');
    }

    public function alphaVantage()
    {
        return view('profile.alphavantage');
    }
}
