@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Home</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <div class="col-md-12 text-justify">

                        <p>¡Bienvenid@s! Esta app permite guardar y centralizar las evidencias de trabajo de todos los alumnos y alumnas matriculadas en la asignatura
                        Evolución y Gestión de la Configuración para las Jornadas InnoSoft Days 2019</p>

                        <p>Este portal web ha sido desarrollado por la Secretaría de Presidencia de las jornadas. Si crees que hay algún error o bug, no dudes en comunicarlo
                        <a href="mailto:davromorg@alum.us.es">aquí</a>.</p>

                        <p>Si tienes dudas acerca del manejo de este portal, <a href="">descarga nuestra guía para las evidencias</a>.</p>

                        <p>¡Muchas gracias!
                        <br>Secretaría General de INNOSOFT DAYS 2019</p>
                        
                    </div>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
