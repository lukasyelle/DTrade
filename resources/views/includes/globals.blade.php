<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

@if (Session()->has('api_key'))
    <!-- API Token -->
    <meta name="api-key" content="{{ session()->get('api_key')[0] }}">
@endif

<!-- Fonts -->
<link rel="dns-prefetch" href="//fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet" type="text/css">

<!-- Scripts -->
<script src="{{ asset('js/app.js') }}" defer></script>


<!-- Styles -->
<link href="{{ asset('css/app.css') }}" rel="stylesheet">