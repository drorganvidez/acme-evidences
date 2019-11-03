@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-12">
                <div class="card">

                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="/home">Home</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Mis asistencias</li>
                        </ol>
                    </nav>



                        <div class="card-body">


                            <div class="d-sm-flex" style="margin-bottom: 20px">
                                <div class="ml-auto">
                                    <p style="font-size: 25px">
                                        <span class="badge badge-light">{{$total_horas}} horas de asistencia a reuniones</span>
                                    </p>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="thead-light">
                                    <tr>
                                        <th scope="col">Horas</th>
                                        <th scope="col">Título de la reunión</th>
                                        <th scope="col">Tipo</th>
                                        <th scope="col">Lugar</th>
                                        <th scope="col">Fecha y hora</th>
                                        <th scope="col">Creada</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($meetings as $meeting)
                                        <tr scope="row">
                                            <td>{{$meeting->hours}}</td>
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
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>

                            <div class="jumbotron" style="padding: 20px">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <p>
                                        <h2>Asistencia a reuniones</h2>
                                        Aquí puedes consultar todo el cómputo de horas de asistencia a reuniones realizadas
                                        durante las Jornadas InnoSoft Days 2019. Recuerda que el coordinador y/o secretario
                                        de tu comité está obligado a registrar las reuniones que hayáis realizado.
                                        </p>
                                        <p>
                                        Si consideras que hay un error, por favor, <b>ponte en contacto con el secretario y/o
                                            coordinador de tu comité.</b>
                                        </p>
                                    </div>

                                </div>
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
                                               role="button">Sí, eliminar reunión</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach




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
