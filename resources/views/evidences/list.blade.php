@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    @if(request()->is('evidences/comite'))

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Evidencias de mi comité</li>
                            </ol>
                        </nav>

                    @elseif(Auth::user()->is_administrator == 1)

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Todas las evidencias</li>
                            </ol>
                        </nav>

                    @else

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Mis evidencias</li>
                            </ol>
                        </nav>

                    @endif


                    <div class="card-body">

                        @if(request()->is('evidences/comite') || Auth::user()->is_administrator == 1)

                            @if(Auth::user()->is_administrator)
                                <form class="form-inline" method="POST" action="{{ route('search.administrator') }}"
                            @elseif(Auth::user()->is_comite)
                                <form class="form-inline" method="POST" action="{{ route('search.comite') }}"
                                      @endif

                                      enctype="multipart/form-data">
                                    @csrf
                                    <label class="sr-only" for="inlineFormInputName2">Name</label>
                                    <input type="text" style="width:300px" class="form-control mb-2 mr-sm-2"
                                           id="search" name="search" placeholder="Buscar alumnos y evidencias...">
                                    <button type="submit" class="btn btn-primary mb-2">Buscar</button>
                                </form>
                            @endif

                            @if(request()->is('evidences/comite'))

                                <div class="d-sm-flex" style="margin-bottom: 20px">
                                    <h2>
                                    <span class="badge badge-dark">
                                        {{$comite->title}}
                                    </span>
                                    </h2>
                                    <br>
                                    <div class="ml-auto">
                                        <a class="btn btn-primary" href="{{ route('evidences.export',$comite->id) }}"
                                           role="button"><i class="far fa-file-excel"></i> Exportar a excel</a>
                                    </div>
                                </div>

                            @elseif(Auth::user()->is_administrator == 1)
                                <div class="d-sm-flex" style="margin-bottom: 20px">
                                    <h2><span class="badge badge-light">Todos los comités</span></h2>
                                    <div class="ml-auto">
                                        <a class="btn btn-primary" href="{{ route('evidences.export',0) }}"
                                           role="button"><i class="far fa-file-excel"></i> Exportar a excel</a>
                                    </div>
                                </div>

                            @endif

                            @if(request()->is('evidences/list'))
                                <div class="d-sm-flex" style="margin-bottom: 20px">
                                {{ $evidences->links() }}
                                    <div class="ml-auto">
                                        <p style="font-size: 25px">
                                            <span class="badge badge-light">{{$count}} evidencias registradas</span>
                                            <span class="badge badge-light">{{$total_horas}} horas registradas</span>
                                        </p>
                                    </div>
                                </div>
                            @else
                                {{ $evidences->links() }}
                            @endif


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

                            {{ $evidences->links() }}

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
