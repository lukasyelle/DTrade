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
                    <el-card>
                        <h3 slot="header">Detailed Projections</h3>
                        {!! $charts['projections']->container() !!}
                    </el-card>
                </el-col>
                <el-col :lg="14" :md="12">
                    <el-row :gutter="20">
                        <el-col :span="8">
                            <el-card>
                                <h3 slot="header">Next Day</h3>
                                <div>
                                    @php $verdict = $stock->nextDay['projection']['verdict'] @endphp
                                    {{ $stock->nextDay['accuracy']['accuracy_'.str_replace(' ', '_', $verdict)] * 100 }}% chance of a
                                    {{ ucfirst($verdict) }}
                                </div>
                            </el-card>
                        </el-col>
                        <el-col :span="8">
                            <el-card>
                                <h3 slot="header">Five Day</h3>
                                <div>
                                    @php $verdict = $stock->fiveDay['projection']['verdict'] @endphp
                                    {{ $stock->fiveDay['accuracy']['accuracy_'.str_replace(' ', '_', $verdict)] * 100 }}% chance of a
                                    {{ ucfirst($verdict) }}
                                </div>
                            </el-card>
                        </el-col>
                        <el-col :span="8">
                            <el-card>
                                <h3 slot="header">Ten Day</h3>
                                <div>
                                    @php $verdict = $stock->tenDay['projection']['verdict'] @endphp
                                    {{ $stock->fiveDay['accuracy']['accuracy_'.str_replace(' ', '_', $verdict)] * 100 }}% chance of a
                                    {{ ucfirst($verdict) }}
                                </div>
                            </el-card>
                        </el-col>
                    </el-row>
                    <el-row>
                        <el-col>
                            <el-card>

                            </el-card>
                        </el-col>
                    </el-row>
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
