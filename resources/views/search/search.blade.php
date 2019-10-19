@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    @if(Auth::user()->is_comite)
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item"><a href="/evidences/comite">Evidencias de mi comité</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Buscador</li>
                            </ol>
                        </nav>
                    @else
                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item"><a href="/evidences/all">Todas las evidencias</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Buscador</li>
                            </ol>
                        </nav>
                    @endif



                    <div class="card-body">

                        <div class="row">

                            <div class="col-lg-12">

                                @if(Auth::user()->is_administrator)
                                    <form class="form-inline" method="POST" action="{{ route('search.administrator') }}"
                                @elseif(Auth::user()->is_comite)
                                    <form class="form-inline" method="POST" action="{{ route('search.comite') }}"
                                @endif

                                <form class="form-inline" method="POST" action="{{ route('search') }}"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <label class="sr-only" for="inlineFormInputName2">Name</label>
                                    <input type="text" value="{{$s}}" style="width:300px" class="form-control mb-2 mr-sm-2"
                                           id="search" name="search" placeholder="Buscar alumnos y evidencias..." autofocus>
                                    <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                                </form>
                            </div>

                            <div class="col-lg-12">
                                Buscando todas las coincidencias con "{{$s}}"
                                <br>
                            </div>

                            <div class="col-lg-12">
                                <h1 style="margin-top: 20px">USUARIOS</h1>
                            </div>

                            @foreach($users as $user)

                                <div class="col-lg-6">

                                    <div class="jumbotron" style="padding: 20px">
                                        <h2>{{$user->surname}}, {{$user->name}}</h2>
                                        <p>UVUS: {{$user->uvus}}</p>
                                        <p>Email: {{$user->email}}</p>
                                        <p>Participación en Jornadas: {{$user->journeys_participation}}</p>
                                        <p>Trabajo en las jornadas: {!! $user->journeys_description !!}</p>
                                    </div>

                                </div>

                            @endforeach

                            <div class="col-lg-12">
                                <h1>EVIDENCIAS</h1>
                            </div>

                            @foreach($evidences as $evidence)

                                <div class="col-lg-6">

                                    <div class="jumbotron" style="padding: 20px">
                                        <h2>{{$evidence->title}}</h2>
                                        <p>#Referencia: {{$evidence->id}}</p>
                                        <p>Horas empleadas: {{ $evidence->hours }}</p>
                                        <p>Descripción: {!! $evidence->description !!}</p>

                                        <a class="btn btn-primary btn-sm"
                                           href="{{ route('evidences.view',$evidence->id) }}" role="button">
                                            <i class="far fa-eye"></i>
                                            <span class="d-none d-lg-block">
                                                Ver evidencia
                                            </span>
                                        </a>

                                        <a class="btn btn-danger btn-sm" href="" role="button" data-toggle="modal"
                                           data-target="#delete_{{$evidence->id}}">
                                            <i class="fas fa-trash"></i>
                                            <div class="d-none d-lg-block">
                                                Eliminar
                                            </div>
                                        </a>

                                    </div>

                                </div>

                            @endforeach

                        </div>


                    </div>

                @foreach($evidences as $evidence)

                    <!-- Modal -->
                        <div class="modal fade" id="delete_{{$evidence->id}}" tabindex="-1" role="dialog"
                             aria-labelledby="exampleModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="exampleModalLabel">¿Estás seguro?</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="d-flex" style="margin-bottom: 20px">
                                            <h2>{{$evidence->title}}</h2>
                                            <div class="ml-auto">
                                                <h4>{{ \Carbon\Carbon::parse($evidence->created_at)->diffForHumans() }}</h4>
                                            </div>
                                        </div>

                                        {!! $evidence->description !!}

                                        <div class="alert alert-danger" role="alert">
                                            ADVERTENCIA: Esto borrará la evidencia <b> y todos sus ficheros
                                                adjuntos.</b>
                                            Este proceso es <b>IRREVERSIBLE</b>.
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                                        </button>
                                        <a class="btn btn-danger" href="{{ route('evidences.delete',$evidence->id) }}"
                                           role="button">Sí, eliminar evidencia</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
@endsection
