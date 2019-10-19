@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    @if(Auth::user()->is_administrator == 1)

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{route('evidences.all')}}">Todas las
                                        evidencias</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$evidence->title}}</li>
                            </ol>
                        </nav>

                    @elseif(request()->is('evidences/comite/view/*'))

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{route('evidences.comite')}}">Evidencias de mi
                                        comité</a></li>
                                <li class="breadcrumb-item active" aria-current="page">{{$evidence->title}}</li>
                            </ol>
                        </nav>

                    @else

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item"><a href="{{route('evidences.list')}}">Mis evidencias</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">{{$evidence->title}}</li>
                            </ol>
                        </nav>

                    @endif


                    <div class="card-body">

                        @if (session('status'))
                            <div class="alert alert-success">
                                {{ session('status') }}
                            </div>
                        @endif

                        @if(request()->is('evidences/comite/*') || request()->is('evidences/all') || Auth::user()->is_administrator)

                            <div class="row" style="padding-bottom: 20px">


                                <div class="col-lg-12">

                                    @if($evidence->check == -1)
                                        <span class="badge badge-secondary">Pendiente de aprobación</span>
                                        <br>
                                        <a class="btn btn-danger"
                                           href="{{ route('evidences.check.reject',$evidence->id) }}"
                                           role="button"><i class="fas fa-times"></i> No dar visto bueno a esta
                                            evidencia</a>
                                        <a class="btn btn-success"
                                           href="{{ route('evidences.check',$evidence->id) }}"
                                           role="button"><i class="fas fa-check"></i> Dar visto bueno a esta
                                            evidencia</a>
                                    @elseif($evidence->check == 0)
                                        <a class="btn btn-success"
                                           href="{{ route('evidences.check',$evidence->id) }}"
                                           role="button"><i class="fas fa-check"></i> Dar visto bueno a esta
                                            evidencia</a>
                                    @else
                                        <a class="btn btn-danger"
                                           href="{{ route('evidences.check',$evidence->id) }}"
                                           role="button"><i class="fas fa-times"></i> No dar visto bueno a esta
                                            evidencia</a>
                                    @endif

                                </div>


                            </div>
                        @endif

                        @if(request()->is('evidences/comite/*') || request()->is('evidences/all') || Auth::user()->is_administrator)

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

                                    <div class="col-lg-8">
                                        <span class="badge  badge-secondary">{{$implicacion}}</span>

                                        {!! $user->journeys_description !!}
                                    </div>

                                </div>
                            </div>

                        @endif

                        <div class="row">

                            <div class="col-lg-8">
                                <h1>{{$evidence->title}}</h1>

                                <p>{!! $evidence->description !!}</p>
                            </div>

                            <div class="col-lg-4">


                                <span class="badge badge-dark">
                                    {{$comite->title}}
                                    @if(isset($comite->subtitle))
                                        / {{$comite->subtitle}}
                                    @endif
                                </span>


                                <h2><span class="badge badge-light">{{$evidence->hours}} horas empleadas</span></h2>
                                <h2><span
                                        class="badge badge-light">{{ \Carbon\Carbon::parse($evidence->created_at)->diffForHumans() }}</span>
                                </h2>

                            </div>

                        </div>


                        <h3>Ficheros adjuntos</h3>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead class="thead-light">
                                <tr>
                                    <th scope="col">Tipo</th>
                                    <th scope="col">Nombre</th>
                                    <th scope="col">Tamaño</th>
                                    <th scope="col">Fecha de subida</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($proofs as $proof)
                                    <tr scope="row">
                                        <td>

                                            @if($proof->file_type == "png" || $proof->file_type == "jpeg" || $proof->file_type == "jpg")
                                                <h2 class="text-center"><i class="far fa-file-image"></i></h2>


                                            @elseif($proof->file_type == "pdf")
                                                <h2 class="text-center"><i class="far fa-file-pdf"></i></h2>


                                            @elseif($proof->file_type == "doc" || $proof->file_type == "docx")
                                                <h2 class="text-center"><i class="far fa-file-word"></i></h2>

                                            @elseif($proof->file_type == "xls" || $proof->file_type == "xlsx")
                                                <h2 class="text-center"><i class="far fa-file-excel"></i></h2>

                                            @elseif($proof->file_type == "zip" || $proof->file_type == "tar.gz" || $proof->file_type == "rar")
                                                <h2 class="text-center"><i class="far fa-file-archive"></i></h2>

                                            @else
                                                <h2 class="text-center"><i class="far fa-file"></i></h2>
                                            @endif

                                        </td>
                                        <td> {{$proof->file_name}} </td>
                                        <td> {{$proof->size}} MB</td>
                                        <td> {{ \Carbon\Carbon::parse($proof->created_at)->diffForHumans() }} </td>
                                        <td>
                                            <a class="btn btn-primary btn-sm"
                                               href="{{ route('proof.download',$proof->id) }}"
                                               role="button">Descargar</a>
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
@endsection
