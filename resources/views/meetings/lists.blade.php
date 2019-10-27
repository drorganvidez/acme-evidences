@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Reuniones de mi comité</li>
                            <li class="breadcrumb-item active" aria-current="page">Listas predefinidas</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.main')}}">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('meetings.lists')}}">Listas predefinidas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.list')}}">Reuniones registradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.new')}}">Registrar nueva reunión</a>
                            </li>
                        </ul>

                        <div class="card-body">

                            @if(!request()->is('meetings/lists/edit/*'))

                                <h1>Listas predefinidas de mi comité</h1>
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead class="thead-light">
                                        <tr>
                                            <th scope="col">Título de la lista</th>
                                            <th scope="col">Creada</th>
                                            <th scope="col">Herramientas</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($listas as $lista)
                                            <tr scope="row">
                                                <td>{{$lista->title}}</td>
                                                <td>{{ \Carbon\Carbon::parse($lista->created_at)->diffForHumans() }}</td>
                                                <td>
                                                    <a class="btn btn-primary btn-sm"
                                                       href="{{ route('meetings.lists.edit',$lista->id_list) }}"
                                                       role="button">
                                                        <i class="far fa-edit"></i>
                                                        Editar lista</a>

                                                    <a class="btn btn-danger btn-sm" href="" role="button"
                                                       data-toggle="modal"
                                                       data-target="#delete_{{$lista->id_list}}">
                                                        <i class="fas fa-trash"></i>
                                                        Eliminar
                                                    </a>

                                                </td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>

                                </div>

                            @endif

                            @if(!request()->is('meetings/lists/edit/*'))

                                <h1>Crear nueva lista</h1>

                            @endif

                                @if(request()->is('meetings/lists/edit/*'))

                                    <form class="form" method="POST"
                                          action="{{ route('meetings.lists.update') }}">

                                @else

                                    <form class="form" method="POST"
                                          action="{{ route('meeting.lists.create') }}">

                                @endif



                                @csrf

                                <div class="form-row">
                                    <div class="form-group col-md-6">
                                        <label for="title">Título</label>
                                        <input id="title" type="text"
                                               class="form-control @error('title') is-invalid @enderror"
                                               name="title"
                                               @if(old('title'))
                                               value="{{old('title')}}"
                                               @else
                                               @if(request()->is('meetings/lists/edit/*'))
                                               value="{{$list->title}}"
                                               @endif
                                               @endif required autocomplete="title"
                                               autofocus>
                                        <small class="form-text text-muted">Escribe un título para tu lista.
                                        </small>

                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row">

                                    <div class="col-lg-6">


                                        <label class="sr-only" for="inlineFormInputName2">Name</label>
                                        <input onkeydown="search_function()" type="text"
                                               class="form-control mb-4 mr-sm-4"
                                               id="search" placeholder="Buscar alumnos..." autofocus>


                                        <div class="overflow-auto" id="usuarios_cotejados">

                                        </div>

                                    </div>

                                    <div class="col-lg-6">


                                        <div class="jumbotron" style="padding: 20px ">

                                            <div class="d-sm-flex" style="margin-bottom: 20px">
                                                <h2><span class="badge badge-dark"><span
                                                                id="alumnos"></span> alumnos</span>
                                                </h2>
                                                <div class="ml-auto">

                                                    @if(request()->is('meetings/lists/edit/*'))
                                                        <input type="hidden" id="id" name="id" value="{{$list->id_list}}"/>
                                                    @endif


                                                    <input type='hidden' id='lista_usuarios'
                                                           name='lista_usuarios'
                                                           value=''
                                                           class="form-control @error('lista_usuarios') is-invalid @enderror"/>

                                                    <button type="submit" class="btn btn-primary"><i
                                                                class="fas fa-list-ul"></i>

                                                        @if(request()->is('meetings/lists/edit/*'))
                                                            Actualizar lista
                                                        @else
                                                            Crear lista
                                                        @endif


                                                    </button>

                                                </div>
                                            </div>

                                            <div id="usuarios_seleccionados">

                                            </div>

                                            @error('lista_usuarios')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror

                                        </div>


                                    </div>

                                </div>

                            </form>
                        </div>

                    </div>


                </div>

            @if(!request()->is('meetings/lists/edit/*'))

                @foreach($listas as $lista)

                    <!-- Modal -->
                        <div class="modal fade" id="delete_{{$lista->id_list}}" tabindex="-1" role="dialog"
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
                                            <h2>{{$lista->title}}</h2>
                                            <div class="ml-auto">
                                                <h4>{{ \Carbon\Carbon::parse($lista->created_at)->diffForHumans() }}</h4>
                                            </div>
                                        </div>

                                        <div class="alert alert-danger" role="alert">
                                            ADVERTENCIA: Esto borrará la lista <b> y todos sus alumnos.</b>
                                            Este proceso es <b>IRREVERSIBLE</b>.
                                        </div>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                                        </button>
                                        <a class="btn btn-danger"
                                           href="{{ route('meetings.lists.delete',$lista->id_list) }}"
                                           role="button">Sí, eliminar lista</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                @endif

            </div>
        </div>
    </div>

    <script>

        @if(request()->is('meetings/lists/edit/*'))

            function load() {

                $.ajax({
                    url: '/meetings/lists/' + $("#id").val(),
                    success: function (respuesta) {
                        usuarios_seleccionados = respuesta;
                        add_list_to_view();
                        lista_usuarios_update();
                        search_function();
                    },
                    error: function () {
                        console.log("No se ha podido obtener la información");
                    }
                });


            }


        @endif

        function add_list_to_view(){

            // reseteamos los alumnos asistentes al cambiar de lista
            $("#usuarios_seleccionados").empty();
            usuarios_cotejados = new Array();
            $("#alumnos").html(usuarios_seleccionados.length);

            for (var i = 0; i < usuarios_seleccionados.length; i++) {

                $("#usuarios_seleccionados").append('<span style="font-size: 20px" id="user_' + usuarios_seleccionados[i].id + '"><a onclick="remove(' + usuarios_seleccionados[i].id + ')" href="#" class="badge badge-danger"><i class="far fa-trash-alt"></i> ' + usuarios_seleccionados[i].surname + ', ' + usuarios_seleccionados[i].name + '</a><br></span>');


            }
        }

        $(document).ready(function () {

            $.ajax({
                url: '{{ route("meetings.ajax") }}',
                success: function (respuesta) {
                    users = respuesta;
                    search_function();
                },
                error: function () {
                    console.log("No se ha podido obtener la información");
                }
            });

            @if(request()->is('meetings/lists/edit/*'))

                load();

            @endif

        });
    </script>

@endsection
