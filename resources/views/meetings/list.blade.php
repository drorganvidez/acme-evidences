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
                            <li class="breadcrumb-item active" aria-current="page">Reuniones registradas</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.main')}}">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.lists')}}">Listas predefinidas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('meetings.list')}}">Reuniones registradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.new')}}">Registrar nueva reunión</a>
                            </li>
                        </ul>

                        <div class="card-body">


                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Título de la reunión</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Lugar</th>
                                        <th scope="col">Fecha y hora</th>
                                        <th scope="col">Creada</th>
                                        <th scope="col">Herramientas</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr scope="row">
                                            <td>{{$meeting->title}}</td>
                                            <td>
                                                @if($meeting->type == 1)
                                                    ORDINARIA
                                                @else
                                                    EXTRAORDINARIA
                                                @endif
                                            </td>
                                            <td>{{$meeting->place}}</td>
                                            <td>{{\Carbon\Carbon::parse($meeting->datetime)->format('d/m/Y h:i')}}</td>
                                            <td>{{ \Carbon\Carbon::parse($meeting->created_at)->diffForHumans() }}</td>
                                            <td>
                                                <a class="btn btn-primary btn-sm"
                                                   href="{{ route('meetings.list.edit',$meeting->id) }}" role="button">
                                                    <i class="far fa-edit"></i>
                                                    Editar reunión</a>

                                                <a class="btn btn-danger btn-sm" href="" role="button"
                                                   data-toggle="modal"
                                                   data-target="#delete_{{$meeting->id}}">
                                                    <i class="fas fa-trash"></i>
                                                    Eliminar
                                                </a>

                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>


                        </div>


                    @foreach($meetings as $meeting)

                        <!-- Modal -->
                            <div class="modal fade" id="delete_{{$meeting->id}}" tabindex="-1" role="dialog"
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
                                                <h2>{{$meeting->title}}</h2>
                                                <div class="ml-auto">
                                                    <h4>{{ \Carbon\Carbon::parse($meeting->created_at)->diffForHumans() }}</h4>
                                                </div>
                                            </div>

                                            <div class="alert alert-danger" role="alert">
                                                ADVERTENCIA: Esto borrará la reunión<b>, todos sus alumnos y las horas computadas de asistencia
                                                    a dicha reunión.</b>
                                                Este proceso es <b>IRREVERSIBLE</b>.
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar
                                            </button>
                                            <a class="btn btn-danger"
                                               href="{{ route('meetings.list.delete',$meeting->id) }}"
                                               role="button">Sí, eliminar lista</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>


                </div>
            </div>
        </div>
    </div>

    <script>

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

        });
    </script>

@endsection
