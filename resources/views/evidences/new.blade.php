@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">

        <div class="col-md-12">
            <div class="card">

                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/home">Home</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Crear evidencia</li>
                    </ol>
                </nav>

                <div class="card-body">

                    <form method="POST" action="{{ route('evidences.create') }}" enctype="multipart/form-data">
                            @csrf

                        <div class="form-row">

                            <div class="form-group col-md-6">
                                <label for="title">Título</label>
                                <input id="title" type="text" class="form-control @error('title') is-invalid @enderror" name="title" value="{{ old('title') }}" required autocomplete="title" autofocus>
                                <small class="form-text text-muted">Escribe un título que describa con precisión tu evidencia (mínimo 5 caracteres)</small>

                                @error('title')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-2">
                                <label for="hours">Horas empleadas</label>
                                <input id="hours" type="number" step="0.01" class="form-control @error('hours') is-invalid @enderror" name="hours" value="{{ old('hours') }}" required autocomplete="hours" autofocus>
                                <small class="form-text text-muted">Números enteros o decimales.</small>

                                @error('hours')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-group col-md-4">
                                <label for="comite">Comité</label>
                                <select id="comite" class="selectpicker form-control @error('comite') is-invalid @enderror" name="comite" value="{{ old('comite') }}" required autofocus>
                                    @foreach($comites as $comite)
                                        <option {{$comite->id == old('comite') ? 'selected' : ''}} value="{{$comite->id}}">
                                            {{$comite->title}}

                                            @if(isset($comite->subtitle))
                                                ({{$comite->subtitle}})
                                            @endif
                                        </option>
                                    @endforeach
                                </select>

                                <small class="form-text text-muted">Elige un comité al que quieres asociar tu evidencia.</small>

                                @error('comite')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                        </div>

                        <div class="form-group">
                            <label for="description">Descripción de la evidencia</label>

                            <textarea id="summernote" name="description" class="form-control @error('description') is-invalid @enderror" required>
                            {{ old('description') }}
                            </textarea>

                            <script>
                                $(document).ready(function() {
                                    $('#summernote').summernote({
                                        placeholder: 'Incluye una breve descripción de tu evidencia',
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

                            <small class="form-text text-muted">Escribe una descripción concisa de tu evidencia (entre 10 y 20000 caracteres).</small>

                            @error('description')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="form-group">
                            <label for="file">Adjuntar archivos</label>
                            <input id="file" name="files[]" type="file" class="file form-control @error('file') is-invalid @enderror"
                            multiple data-show-upload="true" data-show-caption="true"
                            autofocus required accept="image/png, image/jpeg, application/pdf"
                            value="{{ old('files[]') }}">

                            <small class="form-text text-muted">Adjunta archivos que respalden tu evidencia y el número de horas empleadas.</small>

                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <button type="submit" class="btn btn-primary">Crear evidencia</button>

                    </form>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
