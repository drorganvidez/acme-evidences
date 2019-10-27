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
                            <li class="breadcrumb-item active" aria-current="page">Principal</li>
                        </ol>
                    </nav>


                    <div class="card-body">

                        <ul class="nav nav-pills mb-3" id="pills-tab">
                            <li class="nav-item">
                                <a class="nav-link active" href="{{route('meetings.main')}}">Principal</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.lists')}}">Listas predefinidas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.list')}}">Reuniones registradas</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{route('meetings.new')}}">Registrar nueva reunión</a>
                            </li>
                        </ul>

                        <div class="card-body">

                            <p>¡Hola! Desde aquí puedes controlar la asistencia a las reuniones de tu comité.
                                Esta característica se añadió tras un debate en reunión ordinaria de cómo gestionar la
                                asistencia
                                a las reuniones de las Jornadas 2019, de cara a su posterior evaluación.</p>
                            <p>Para facilitar la gestión, te recomendamos crear una lista predefinida.

                            <h3>Preguntas frecuentes</h3>

                            <div class="jumbotron" style="padding: 20px">
                                <div class="row">

                                    <div class="col-lg-12">
                                        <p>
                                        <h2>1. ¿Qué es una lista predefinida?</h2>
                                        Es una lista de gente que normalmente asiste a tus reuniones, así no tendrás que
                                        seleccionar
                                        sus
                                        nombres cada vez que vayas a registrar una reunión.
                                        </p>
                                        <p>
                                        <h2>2. ¿Y si eventualmente viene alguien a la reunión que no está en la
                                            lista?</h2>
                                        ¡Sin problemas! Puedes añadir usuarios adicionales en la parte de "Invitados".
                                        </p>
                                        <p>
                                        <h2>3. ¿Qué pasa si, al tiempo, me doy cuenta de que hay un error?</h2>
                                        ¡No pasa nada! Puedes editar la asistencia, la hora, el lugar y el tiempo de la
                                        reunión
                                        cuantas veces
                                        desees.
                                        </p>
                                    </div>

                                </div>
                            </div>

                        </div>

                    </div>


                </div>
            </div>
        </div>
    </div>
@endsection
