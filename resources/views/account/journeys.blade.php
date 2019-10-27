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
                            <li class="breadcrumb-item active" aria-current="page">Jornadas</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('account')}}">Mi cuenta</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('account.journeys')}}">Jornadas</a>
                            </li>
                        </ul>

                        <div class="card-body">

                            @if(!isset($user->journeys_description) && !isset($user->journeys_participation))
                                <div class="alert alert-primary" role="alert">
                                    Recuerda completar tu información referente a tu trabajo en las Jornadas InnoSoft
                                    para la evaluación de este año.
                                </div>
                            @endif

                            @if (session('status'))
                                <div class="alert alert-success">
                                    {{ session('status') }}
                                </div>
                            @endif

                            <form method="POST" action="{{ route('account.journeys.upload') }}">
                                @csrf

                                <div class="form-row">

                                    <div class="form-group col-md-4">
                                        <label for="participation">Nivel de participación en las jornadas</label>
                                        <select id="participation"
                                                class="selectpicker form-control @error('participation') is-invalid @enderror"
                                                name="participation" value="{{ old('participation') }}" required
                                                autofocus>

                                            <option
                                                {{($user->journeys_participation == old('participation')) || ($user->journeys_participation == 1) ? 'selected' : ''}} value="1">
                                                Organización
                                            </option>
                                            <option
                                                {{($user->journeys_participation == old('participation')) || ($user->journeys_participation == 2) ? 'selected' : ''}} value="2">
                                                Intermedio
                                            </option>
                                            <option
                                                {{($user->journeys_participation == old('participation')) || ($user->journeys_participation == 3) ? 'selected' : ''}} value="3">
                                                Asistencia
                                            </option>
                                        </select>

                                        <small class="form-text text-muted">Elige tu nivel de participación en las
                                            Jornadas
                                            de este año
                                        </small>

                                        @error('participation')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>


                                </div>

                                <div class="form-group">
                                    <label for="description">Descripción de tu trabajo en las jornadas</label>

                                    <textarea id="summernote" name="description"
                                              class="form-control @error('description') is-invalid @enderror" required>
                                    {{ $user->journeys_description }}
                                        {{ old('description') }}
                            </textarea>

                                    <script>
                                        $(document).ready(function () {
                                            $('#summernote').summernote({
                                                placeholder: 'Incluye una descripción de tu trabajo durante las Jornada InnoSoft Days. ' +
                                                'Se valorará para tu evaluación personal.',
                                                height: 300,
                                                minHeight: 300
                                            });
                                        });
                                    </script>

                                    <style>
                                        .note-group-select-from-files {
                                            display: none;
                                        }
                                    </style>

                                    <small class="form-text text-muted">Incluye una descripción de tu trabajo durante
                                        las
                                        Jornada InnoSoft Days.
                                        Se valorará para tu evaluación personal (entre 50 y 20000 caracteres).
                                    </small>

                                    @error('description')
                                    <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary">Actualizar información</button>

                            </form>

                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
