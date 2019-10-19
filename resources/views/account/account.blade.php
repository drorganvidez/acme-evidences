@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mi cuenta</li>
                            <li class="breadcrumb-item active" aria-current="page">Mis datos</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('account')}}">Mis datos</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('account.journeys')}}">Jornadas</a>
                            </li>
                        </ul>

                        <div class="card-body">

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('account.upload') }}">
                                @csrf

                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label for="name">Nombre</label>
                                        <input id="name" type="text"
                                               class="form-control @error('name') is-invalid @enderror" name="name"


                                               @if(old('name'))
                                               value="{{old('name')}}"
                                               @else
                                               value="{{$user->name}}"
                                               @endif

                                               required autocomplete="name" autofocus>

                                        @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="surname">Apellidos</label>
                                        <input id="surname" type="text"
                                               class="form-control @error('surname') is-invalid @enderror"
                                               name="surname"

                                               @if(old('surname'))
                                               value="{{old('surname')}}"
                                               @else
                                               value="{{$user->surname}}"
                                               @endif

                                               required autocomplete="surname" autofocus>

                                        @error('surname')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="uvus">UVUS</label>
                                        <input id="uvus" type="text"
                                               class="form-control @error('uvus') is-invalid @enderror" name="uvus"

                                               @if(old('uvus'))
                                               value="{{old('uvus')}}"
                                               @else
                                               value="{{$user->uvus}}"
                                               @endif

                                               required autocomplete="uvus" autofocus>

                                        @error('uvus')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="email">Email</label>
                                        <input id="email" type="text"
                                               class="form-control @error('email') is-invalid @enderror" name="email"

                                               @if(old('email'))
                                               value="{{old('email')}}"
                                               @else
                                               value="{{$user->email}}"
                                               @endif

                                               required autofocus>

                                        @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="password">Contraseña</label>
                                        <input id="password" type="password"
                                               class="form-control @error('password') is-invalid @enderror"
                                               name="password" autofocus>

                                        @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="password_confirm">Repite contraseña</label>
                                        <input id="password_confirm" type="password"
                                               class="form-control @error('password_confirm') is-invalid @enderror"
                                               name="password_confirm" autofocus>

                                        @error('password_confirm')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>


                                </div>

                                <button type="submit" class="btn btn-primary">Actualizar datos</button>

                            </form>
                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
