<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use App\Evidence;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class SearchController extends Controller
{
    public function search_home()
    {
        return redirect("home");
    }

    public function search(Request $request)
    {

        $s = $request->search;

        /*
            Puede buscar
                - El administrador
                - Los responsables de comités
        */

        $es_administrador = Auth::user()->is_administrator;
        $es_de_comite = Auth::user()->is_comite;
        if (!$es_administrador && !$es_de_comite) {
            return Redirect::back();
        }

        /*
         *  BÚSQUEDA DE ALUMNOS POR
         *
         *      - NOMBRE
         *      - APELLIDOS
         *      - UVUS
         *      - EMAIL
         */

        // buscar alumnos por nombre
        $users = User::whereRaw('lower(name) like (?)', ["%{$s}%"])->get();

        // buscar alumnos por apellidos
        $users = $users->concat(User::whereRaw('lower(surname) like (?)', ["%{$s}%"])->get());

        // buscar alumnos por uvus
        $users = $users->concat(User::whereRaw('lower(uvus) like (?)', ["%{$s}%"])->get());

        // buscar alumnos por email
        $users = $users->concat(User::whereRaw('lower(email) like (?)', ["%{$s}%"])->get());

        // filtramos usuarios repetidos
        $users = $users->unique();

        // SUPER FILTRO: UN ENCARGADO DE COMITÉ SOLO PUEDE VER LOS ALUMNOS QUE HAN APORTADO EVIDENCIAS AL SUYO
        if (Auth::user()->is_comite) {

            $new_users = collect();

            foreach ($users as $user) {

                // solo me quedo con usuarios que hayan enviado evidencias a mi comité
                if ($this->este_usuario_es_de_mi_comite($user)) {
                    $new_users->push($user);
                }

                $users = $new_users;

            }
        }

        /*
         *  BÚSQUEDA DE EVIDENCIAS POR
         *
         *      - ID
         *      - TITULO
         */

        // buscar evidencias por id
        $evidences = Evidence::whereRaw('lower(id) like (?)', ["%{$s}%"])->get();

        // buscar evidencias por titulo
        $evidences = $evidences->concat(Evidence::whereRaw('lower(title) like (?)', ["%{$s}%"])->get());

        // buscar evidencias por horas
        $evidences = $evidences->concat(Evidence::whereRaw('lower(hours) like (?)', ["%{$s}%"])->get());

        // filtramos evidencias repetidos
        $evidences = $evidences->unique();

        // SUPER FILTRO: UN ENCARGADO DE COMITÉ SOLO PUEDE VER LAS EVIDENCIAS APORTADAS A ESTE
        if (Auth::user()->is_comite) {

            $new_evidences = collect();

            foreach ($evidences as $evidence){

                $mi_comite = Auth::user()->id_comite;

                // ¿la evidencia es de logistica?
                if($this->la_evidencia_es_de_logistica($evidence)){
                    if($this->soy_de_logistica($evidence)){
                        $new_evidences->push($evidence);
                    }
                }

                // ¿la evidencia es de comunicacion?
                if($this->la_evidencia_es_de_comunicacion($evidence)){
                    if($this->soy_de_comunicacion($evidence)){
                        $new_evidences->push($evidence);
                    }
                }

                // resto de comités
                if($evidence->id_comite == $mi_comite){
                    $new_evidences->push($evidence);
                }

            }

            $evidences = $new_evidences;

        }

        return view('search.search', ['s' => $s, 'users' => $users, 'evidences' => $evidences]);

    }

    public function este_usuario_es_de_mi_comite($user)
    {

        $mi_comite = Auth::user()->id_comite;
        $evidencias = Evidence::where('id_user', $user->id)->get();


        // ¿soy de logística?
        if ($mi_comite == 7 || $mi_comite == 8 || $mi_comite == 9 || $mi_comite == 10) {
            return $this->este_usuario_es_de_logistica($user, $evidencias);
        }

        // ¿soy de comunicacion?
        if ($mi_comite == 11 || $mi_comite == 12 || $mi_comite == 13 || $mi_comite == 14) {
            return $this->este_usuario_es_de_comunicacion($user, $evidencias);
        }

        // ¿resto de comités?
        return $this->somos_de_mismo_comite($user, $evidencias);

    }

    public function este_usuario_es_de_logistica($user, $evidencias)
    {
        foreach ($evidencias as $evidencia) {
            if ($evidencia->id_comite == 7 || $evidencia->id_comite == 8 || $evidencia->id_comite == 9 || $evidencia->id_comite == 10) {
                return true;
            }
        }
        return false;
    }

    public function este_usuario_es_de_comunicacion($user, $evidencias)
    {
        foreach ($evidencias as $evidencia) {
            if ($evidencia->id_comite == 11 || $evidencia->id_comite == 12 || $evidencia->id_comite == 13 || $evidencia->id_comite == 14) {
                return true;
            }
        }
        return false;
    }

    public function somos_de_mismo_comite($user, $evidencias)
    {
        foreach ($evidencias as $evidencia) {
            if ($evidencia->id_comite == Auth::user()->id_comite) {
                return true;
            }
        }
        return false;
    }

    public function la_evidencia_es_de_logistica($evidence){
        return $evidence->id_comite == 7 || $evidence->id_comite == 8 || $evidence->id_comite == 9 || $evidence->id_comite == 10;
    }

    public function la_evidencia_es_de_comunicacion($evidence){
        return $evidence->id_comite == 11 || $evidence->id_comite == 12 || $evidence->id_comite == 13 || $evidence->id_comite == 14;
    }

    public function soy_de_logistica(){
        $mi_comite = Auth::user()->id_comite;
        return $mi_comite == 7 || $mi_comite == 8 || $mi_comite == 9 || $mi_comite == 10;
    }

    public function soy_de_comunicacion(){
        $mi_comite = Auth::user()->id_comite;
        return $mi_comite == 11 || $mi_comite == 12 || $mi_comite == 13 || $mi_comite == 14;
    }
}
