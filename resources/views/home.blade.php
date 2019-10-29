@extends('layouts.app')

@section('content')
<!-- Page Content -->
<div class="container">

    <!-- Jumbotron Header -->
    <header class="jumbotron my-4">
        <h1 class="display-3" style="font-size: 40px">¡Bienvenid@s!</h1>
        <p class="lead">Esta app permite guardar y centralizar las evidencias de trabajo de todos los alumnos y alumnas matriculadas en la asignatura
            Evolución y Gestión de la Configuración para las Jornadas InnoSoft Days 2019</p>
        <a href="/evidences/new" class="btn btn-primary btn-lg">¡Crear evidencia!</a>
    </header>

    <!-- Page Features -->
    <div class="row text-center">

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <img class="card-img-top" src="/assets/cuenta.png" alt="">
                <div class="card-footer">
                    <a href="account" class="btn btn-primary">Ver mi cuenta</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <img class="card-img-top" src="/assets/guia.png" alt="">
                <div class="card-footer">
                    <a href="assets/GU%C3%8DA%20DE%20USUARIO%20-%20ACME%20EVIDENCES.pdf" class="btn btn-primary">Descargar guía</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <img class="card-img-top" src="/assets/meeting.png" alt="">
                <div class="card-footer">
                    <a href="meetings/main" class="btn btn-primary @if(!Auth::user()->is_comite)
                            disabled
@endif"

                    >Registrar reunión</a>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6 mb-4">
            <div class="card">
                <img class="card-img-top" src="/assets/bug.png" alt="">
                <div class="card-footer">
                    <a href="mailto:davromorg@alum.us.es" class="btn btn-primary">Informar de bug</a>
                </div>
            </div>
        </div>



    </div>

    <section class="jumbotron text-center">
        <div class="container">
            <h1 class="jumbotron-heading">We <i class="fas fa-heart"></i> open source</h1>
            <p class="lead text-muted">Acme Evidences está desarrollado en Laravel 5.8, un framework PHP de código abierto.
            Con objeto de que sirva de utilidad para posteriores cursos y se implementen nuevas funcionalidades, hemos
            liberado su código para que cualquiera pueda colaborar.</p>
            <p>
                <a target="_blank" href="https://github.com/drorganvidez/acme-evidences" class="btn btn-primary my-2">Ver repositorio en Git</a>
            </p>
        </div>
    </section>

    <!-- /.row -->

</div>
<!-- /.container -->


@endsection
