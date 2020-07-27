<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <link rel="stylesheet" href="{{ mix('/css/app.css') }}">
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet" type="text/css">

        <!-- Styles -->
        <style>
            html, body {
                background-color: #fff;
                color: #636b6f;
                /*font-family: 'Nunito', sans-serif;*/
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
                padding: 25px;
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
                position: relative;
            }

            #landing {
                padding-top: 15vh;
                padding-bottom: 9vh;
                background-color: rgba(0, 203, 169, 0.71);
            }

            #landing div {
                height: 45vh;
                background-image: url('/big-logo.png');
                background-size: contain;
                background-position: center;
                background-repeat: no-repeat;
            }

            #wave-container {
                position: relative;
            }

            @keyframes animateWave {
                0% {
                    transform: scale(1, .75);
                }
                100% {
                    transform: scale(1, 1);
                }
            }

            .wave > svg {
                display: block;
                transform-origin: top;
                animation: animateWave 1000ms cubic-bezier(0.23, 1, 0.32, 1) forwards;
            }

            .wave {
                display: inline-block;
                position: relative;
                width: 100%;
                vertical-align: middle;
                overflow: hidden;
                top: 0;
                left: 0;
            }

            #content-1 {
                padding: 10px 30px 30px;
            }

            #content-1 h1 {
                font-size: 36px;
                margin-top: 0;
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
            <section id="landing"><div></div></section>
            <section id="wave-container">
                <div class="wave">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 200"><path fill="#00CBA9" fill-opacity="0.7" d="M0,32L48,64C96,96,192,160,288,170.7C384,181,480,139,576,101.3C672,64,768,32,864,58.7C960,85,1056,171,1152,176C1248,181,1344,107,1392,69.3L1440,32L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path></svg>
                </div>
            </section>
            <section id="content-1">
                <article class="center">
                    <h1>Automated Stock Analysis</h1>
                    <p class="half-width center padding-bottom padding-top margin-top">DTrade empowers its users with up-to-date stock information and machine learning analysis of said data. The information provided by our algorithm is used to power projection displays, and user-specific portfolio sizing recommendations based on the Kelly Criterion.</p>
                </article>
            </section>
        </main>
    </body>
</html>
