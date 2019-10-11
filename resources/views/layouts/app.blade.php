<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>

    @include("includes.globals")

    @yield('head')

    <title>@yield('title') | {{ config('app.name', 'Laravel') }}</title>

</head>
<body>

    <div id="app">

        @include("includes.header")

        <el-container>
            <el-main>
                @yield('body')
            </el-main>
        </el-container>

    </div>

    @yield("scripts")

</body>
</html>
