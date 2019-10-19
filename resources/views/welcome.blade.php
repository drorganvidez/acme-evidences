<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css">

        <title>Acme-Evidences</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet">

        <!-- Styles -->
        <style>
            html, body {
                color: #636b6f;
                font-family: 'Nunito', sans-serif;
                font-weight: 200;
                height: 100vh;
                margin: 0;
                
            }

        </style>
    </head>
    <body>

    <div class="container h-100">
        <div class="row h-100 justify-content-center align-items-center text-center">

         <div class="col-12">

           

                <div style="margin: 20px 0px">

                    <img src="assets/logo.jpg" class="responsive" width="200px"/>

                    <h3 style="padding-top: 20px; font-size: 20px">Jornadas InnoSoft Days 2019</h3>

                    <h1>Acme Evidences</h1>
                    
                </div>

                @if (Route::has('login'))
                
                @auth
                    <a class="btn btn-lg btn-dark" href="{{ url('/home') }}" role="button">Home</a>
                @else
                    <a class="btn btn-lg btn-dark" href="{{ url('/login') }}" role="button">Login</a>

                    @if (Route::has('register'))
                        <a class="btn btn-lg btn-dark" href="{{ url('/register') }}" role="button">Registro</a>
                    @endif
                @endauth
            
        @endif

                
        </div>
  
        </div>
    </div>
        
    </body>
</html>
