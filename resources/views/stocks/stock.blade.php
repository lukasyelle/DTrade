@extends('layouts.stocks')

@section('title', $stock->symbol)

@section('content')
    <el-container>
        <el-header>
            <h1>{{ $stock->symbol }}</h1>
            <el-breadcrumb separator="/">
                <el-breadcrumb-item><a href="/stocks">Stocks</a></el-breadcrumb-item>
                <el-breadcrumb-item>{{ $stock->symbol }}</el-breadcrumb-item>
            </el-breadcrumb>
        </el-header>
        <el-main>
            <el-row :gutter="20">
                <el-col :lg="10" :md="16" :xs="24">
                    <stock-today-card :stock="{{ $stock }}"></stock-today-card>
                </el-col>
                <el-col :lg="14" :md="12">
                    <stock-summary-projections-card :stock="{{ $stock }}"></stock-summary-projections-card>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-card>
                        <h3 slot="header">Detailed Projections</h3>
                        {!! $charts['projections']->container() !!}
                    </el-card>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-card>
                        {!! $charts['price']->container() !!}
                    </el-card>
                </el-col>
            </el-row>
            <el-row>
                <el-col>
                    <el-card>
                        {!! $charts['indicators']->container() !!}
                    </el-card>
                </el-col>
            </el-row>
        </el-main>
    </el-container>
@endsection

@section ('scripts')
    @foreach ($charts as $chart)
        {!! $chart->script() !!}
    @endforeach
@endsection
