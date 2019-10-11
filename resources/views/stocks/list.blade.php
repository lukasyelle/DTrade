@extends('layouts.stocks')

@section('title', 'All Stocks')

@section('content')
    <el-container>
        <el-header>
            <h1>All Stocks</h1>
        </el-header>
        <el-main>
            <el-row :gutter="20">
                <el-col :span="16">
                    @foreach($stocks as $stock)
                        <el-row>
                            <el-col>
                                <el-card onclick="window.location.href='/stocks/{{ $stock->symbol }}'" class="cursor">
                                    <h3>{{ $stock->symbol }} - ${{ $stock->value }} (as of {{ $stock->lastUpdate->created_at->toDateString() }})</h3>
                                    {{ round($stock->nextDay['projection']->probabilityProfit * 100) }}% Chance Of Next Day Profit
                                </el-card>
                            </el-col>
                        </el-row>
                    @endforeach
                </el-col>
            </el-row>
        </el-main>
    </el-container>
@endsection
