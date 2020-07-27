<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
            }

            nav {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                height: 80px;
                z-index: 1;
            }

            nav ul {
                float: right;
                padding: 15px;
                display: inline-block;
                list-style-type: none;
                background-color: #fff;
            }

            nav ul li {
                float: left;
                padding: 0 25px;
                font-size: 13px;
                font-weight: 600;
                letter-spacing: .1rem;
                text-transform: uppercase;
            }

            nav ul li a {
                color: #636b6f;
                text-decoration: none;
            }

            main {
                z-index: 0;
                min-height: 90vh;
                padding-top: 10vh;
                position: relative;
            }

            #landing {
                height: 70vh;
                background-image: url('/big-logo.png');
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
            }
        </style>
    </head>
    <body>
        <nav>
            <ul>
                @auth
                    <li><a href="{{ route('stocks.all') }}">Stocks</a></li>
                    <li><a href="{{ route('profile.index') }}">Profile</a></li>
                @else
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('register') }}">Register</a></li>
                @endauth
            </ul>
        </nav>
        <main>
            <section id="landing">

            </section>
        </main>
    </body>
</html>
