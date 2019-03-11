@extends('layouts.app')

@section('title', 'Home')

@section('head')
    <link rel="stylesheet" href="{{ mix('/css/home.css') }}">
@endsection

@section('body')
    <el-row :gutter="20">
        <el-col :span="16">
            <el-card>
                <div slot="header">
                    <h3>Dashboard</h3>
                </div>
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif
                </div>
            </el-card>
        </el-col>
        <el-col :span="8">
            <el-card shadow="always">
                <div slot="header">
                    <h3>Portfolios</h3>
                </div>
                @foreach($portfolios as $portfolio)
                    <el-card class="portfolio" shadow="hover">
                        <div slot="header">
                            <h3 class="text-capitalize">{{ ucfirst($portfolio['platform']) }}</h3>
                        </div>
                        <p>Value: <strong>${{ $portfolio['portfolio_value'] }}</strong></p>
                        <span>Last Updated {{ $portfolio['updated_at'] }}</span>
                    </el-card>
                @endforeach
            </el-card>
        </el-col>
    </el-row>
</div>
@endsection
