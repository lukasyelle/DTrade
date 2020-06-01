@extends('layouts.app')

@section('head')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/echarts/4.3.0/echarts.min.js" charset="utf-8"></script>
    <link rel="stylesheet" href="{{ mix('/css/profile.css') }}">
@endsection

@section('body')
    <div class="profile">
        @yield('content')
    </div>
@endsection
