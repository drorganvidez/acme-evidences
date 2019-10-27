@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    @if(request()->is('meetings/list/edit/*'))

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Reuniones de mi comité</li>
                                <li class="breadcrumb-item active" aria-current="page"><a href="/meetings/list">Reuniones registradas</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Editando: {{$meeting->title}}</li>
                            </ol>
                        </nav>

                    @else

                        <nav aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="/home">Home</a></li>
                                <li class="breadcrumb-item active" aria-current="page">Reuniones de mi comité</li>
                                <li class="breadcrumb-item active" aria-current="page">Registrar nueva reunión</li>
                            </ol>
                        </nav>

                    @endif


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.main')}}">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.lists')}}">Listas predefinidas</a>
                            </li>

                            @if(request()->is('meetings/list/edit/*'))
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('meetings.list')}}">Reuniones registradas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('meetings.new')}}">Registrar nueva reunión</a>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('meetings.list')}}">Reuniones registradas</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="{{route('meetings.new')}}">Registrar nueva reunión</a>
                                </li>
                            @endif



                        </ul>

                        <div class="card-body">

                            @if(request()->is('meetings/list/edit/*'))

                                <form method="POST"
                                      action="{{ route('meeting.update') }}">

                            @else

                                <form method="POST"
                                      action="{{ route('meeting.create') }}">

                            @endif



                                @csrf

                                @if(request()->is('meetings/list/edit/*'))
                                    <input type='hidden' id='id' name='id' value='{{$meeting->id}}' />
                                @endif

                                <input type='hidden' id='lista_usuarios'
                                       name='lista_usuarios'
                                       value=''
                                       class="form-control @error('lista_usuarios') is-invalid @enderror"/>

                                <div class="form-row">

                                    <div class="form-group col-md-6">
                                        <label for="title">Título</label>
                                        <input id="title" type="text"
                                               class="form-control @error('title') is-invalid @enderror" name="title"
                                               @if(old('title'))
                                                value="{{old('title')}}"
                                               @elseif(request()->is('meetings/list/edit/*'))
                                                   value="{{$meeting->title}}"
                                               @endif
                                               required autocomplete="title" autofocus>
                                        <small class="form-text text-muted">Escribe un título para tu reunión.
                                        </small>

                                        @error('title')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-2">
                                        <label for="hours">Horas empleadas</label>
                                        <input id="hours" type="number" step="0.01"
                                               class="form-control @error('hours') is-invalid @enderror" name="hours"
                                               @if(old('hours'))
                                                    value="{{old('hours')}}"
                                               @elseif(request()->is('meetings/list/edit/*'))
                                                    value="{{$meeting->hours}}"
                                               @endif required autocomplete="hours" autofocus>
                                        <small class="form-text text-muted">Números enteros o decimales.</small>

                                        @error('hours')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="type">Tipo de reunión</label>
                                        <select id="type"
                                                class="selectpicker form-control @error('type') is-invalid @enderror"
                                                name="type" value="{{ old('type') }}" required autofocus>

                                            <option
                                                    @if(old('type'))
                                                        @if(old('type') == 1)
                                                            selected
                                                        @endif
                                                    @elseif(request()->is('meetings/list/edit/*'))
                                                        @if($meeting->type == 1)
                                                            selected
                                                        @endif
                                                    @endif
                                                    value="1">ORDINARIA
                                            </option>
                                            <option
                                                    @if(old('type'))
                                                        @if(old('type') == 2)
                                                            selected
                                                        @endif
                                                    @elseif(request()->is('meetings/list/edit/*'))
                                                        @if($meeting->type == 2)
                                                            selected
                                                        @endif
                                                    @endif
                                                    value="2">EXTRAORDINARIA</option>

                                        </select>

                                        <small class="form-text text-muted">Elige el tipo de reunión que has realizado.
                                        </small>

                                        @error('type')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="place">Lugar</label>
                                        <input id="place" type="text"
                                               class="form-control @error('place') is-invalid @enderror" name="place"
                                               @if(old('place'))
                                               value="{{old('place')}}"
                                               @elseif(request()->is('meetings/list/edit/*'))
                                               value="{{$meeting->place}}"
                                               @endif
                                               required autocomplete="place" autofocus>
                                        <small class="form-text text-muted">Indica el lugar de la reunión.
                                        </small>

                                        @error('place')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="datetime">Día y hora</label>
                                        <input id="datetime" type="datetime-local"
                                               class="form-control @error('datetime') is-invalid @enderror" name="datetime"
                                               @if(old('datetime'))
                                               value="{{old('datetime')}}"
                                               @elseif(request()->is('meetings/list/edit/*'))
                                               value="{{\Carbon\Carbon::parse($meeting->datetime)->format('Y-m-d\Th:i:s')}}"
                                               @endif
                                               required autofocus>
                                        <small class="form-text text-muted">Indica el día y la hora de la reunión.
                                        </small>

                                        @error('datetime')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror
                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="col-lg-6">


                                        <label for="search">Añadir asistentes adicionales a la reunión</label>


                                        <input onkeydown="search_function()" type="text"
                                               class="form-control mb-4 mr-sm-4"
                                               id="search" placeholder="Buscar alumnos..." autofocus>


                                        <div class="" id="usuarios_cotejados" style="height:150px; overflow-y: scroll;">

                                        </div>

                                    </div>

                                    <div class="col-lg-6">

                                        <label for="comite">Elige una lista predefinida</label>
                                        <select id="lista" onchange="getLista(this);"
                                                class="selectpicker form-control @error('lista') is-invalid @enderror"
                                                name="lista" value="{{ old('lista') }}" required autofocus>

                                            <option seleted value="-1">Seleccionar...</option>
                                            @foreach($listas as $lista)
                                                <option value="{{$lista->id_list}}">{{$lista->title}}</option>
                                            @endforeach

                                        </select>

                                        @error('lista')
                                        <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                        @enderror

                                        <br>

                                        <div class="jumbotron" style="margin-top: 20px; padding: 20px ">


                                            <div class="d-sm-flex" style="margin-bottom: 20px">
                                                <h2><span class="badge badge-dark"><span
                                                                id="alumnos"></span> alumnos asistentes</span>
                                                </h2>
                                                <div class="ml-auto">

                                                </div>
                                            </div>

                                            <div id="usuarios_seleccionados">

                                            </div>

                                        </div>


                                    </div>

                                </div>

                                <div class="form-row">

                                    <div class="col-lg-12">

                                        @if(request()->is('meetings/list/edit/*'))

                                            <button type="submit" class="btn btn-primary">Actualizar reunión</button>

                                        @else

                                            <button type="submit" class="btn btn-primary">Registrar reunión</button>

                                        @endif

                                    </div>

                                </div>

                            </form>

                        </div>

                    </div>

                    <script>

                        function load_attendees(){
                            $.ajax({
                                url: '/meetings/list/attendees/' + $("#id").val(),
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


                        function getLista(sel){

                            if(sel.value != -1) {

                                $.ajax({
                                    url: '/meetings/lists/' + sel.value,
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
                        }

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

                            @if(request()->is('meetings/list/edit/*'))
                                load_attendees();
                            @endif

                        });
                    </script>

                </div>
            </div>
        </div>
    </div>
@endsection
