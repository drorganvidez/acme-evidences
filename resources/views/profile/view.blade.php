@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Perfil de usuario</li>
                            <li class="breadcrumb-item active" aria-current="page">{{$user->surname}}, {{$user->name}}</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <div class="card-body">


                            <div class="jumbotron" style="padding: 20px">
                                <div class="row">

                                    <div class="col-lg-8">
                                        <h2>{{$user->surname}}, {{$user->name}} ({{$user->uvus}})</h2>
                                    </div>

                                    <div class="col-lg-4">
                                        <h2><span class="badge badge-dark"><a style="color: white"
                                                                              href="mailto:{{$user->email}}">{{$user->email}}</a></span>
                                        </h2>
                                    </div>

                                    @if($comite != null)
                                        <div class="col-lg-12">
                                            <span class="badge  badge-secondary">{{$comite->title}}</span>
                                        </div>
                                    @endif



                                    <div class="col-lg-8">
                                        <span class="badge  badge-secondary">{{$implicacion}}</span>

                                        {!! $user->journeys_description !!}
                                    </div>

                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Referencia</th>
                                        <th scope="col">Creada</th>
                                        <th scope="col">Título</th>
                                        <th scope="col">Horas</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Herramientas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($evidences as $evidence)
                                        <tr scope="row">
                                            <td> {{$evidence->id}} </td>
                                            <td> {{ \Carbon\Carbon::parse($evidence->created_at)->diffForHumans() }} </td>
                                            <td> {{$evidence->title}} </td>
                                            <td> {{$evidence->hours}} </td>
                                            <td style="text-align: center">
                                                @if($evidence->check == -1)
                                                    <h2><i class="fas fa-clock"></i></h2>
                                                @elseif($evidence->check == 0)
                                                    <h2><i class="fas fa-times"></i></h2>
                                                @else
                                                    <h2><i class="fas fa-check"></i></h2>
                                                @endif
                                            </td>
                                            <td>

                                                @if(request()->is('evidences/comite'))
                                                    <a class="btn btn-primary btn-sm"
                                                       href="{{ route('evidences.comite.view',$evidence->id) }}"
                                                       role="button">
                                                        <i class="far fa-eye"></i>
                                                        <span class="d-none d-lg-block">
                                                Ver evidencia
                                            </span>
                                                    </a>
                                                @else
                                                    <a class="btn btn-primary btn-sm"
                                                       href="{{ route('evidences.view',$evidence->id) }}" role="button">
                                                        <i class="far fa-eye"></i>
                                                        <span class="d-none d-lg-block">
                                                Ver evidencia
                                            </span>
                                                    </a>
                                                @endif

                                                <a class="btn btn-danger btn-sm" href="" role="button"
                                                   data-toggle="modal"
                                                   data-target="#delete_{{$evidence->id}}">
                                                    <i class="fas fa-trash"></i>
                                                    <div class="d-none d-lg-block">
                                                        Eliminar
                                                    </div>
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>


                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
