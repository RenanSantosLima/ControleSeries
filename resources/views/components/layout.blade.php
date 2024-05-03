<!DOCTYPE html>
<html>
    <head>
        <title>{{ $title }} - Controle de Séries</title>
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="container-fluid">
              <a class="navbar-brand" href="{{ route('series.index') }}">Home</a>

              @auth
              <form action="{{ route('logout') }}" method="get">
                <button class="btn btn-link">
                    Sair
                </button>
              </form>
              @endauth

              @guest
              <a href="{{ route('login') }}">Entrar</a>
              @endguest
            </div>
        </nav>

        <div class="container">
            <h1>{{ $title }}</h1>
            @isset($mensagemSucesso)
                <div class="alert alert-success">
                    {{ $mensagemSucesso }}
                </div>
            @endisset

            @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
            @endif

            {{ $slot }}
        </div>
    </body>
</html>
