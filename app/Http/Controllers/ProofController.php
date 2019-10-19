<?php

namespace App\Http\Controllers;

use App\Proof;
use App\Evidence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProofController extends Controller
{
    public function download($id){

        /*
            Puede descargar la prueba de una evidencia:
                - El administrador
                - El responsable del comité de esa evidencia
                - El propietario de esa prueba
        */

        $proof = Proof::find($id);

        if($proof == null){
            return redirect('home');
        }

        $evidence = Evidence::find($proof->id_evidence);
        $id_comite = $evidence->id_comite;

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($id_comite);
        $es_mi_evidencia = Auth::user()->id == $evidence->id_user;

        if(!$es_administrador && !$es_de_mi_comite && !$es_mi_evidencia){
            return redirect("/home");
        }

        return Storage::download($proof->file_route);
    }

    public function es_de_logistica($id_comite){
        return $id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10;
    }

    public function es_de_comunicacion($id_comite){
        return $id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14;
    }

    public function soy_de_un_comite(){
        return Auth::user()->is_comite;
    }

    public function soy_de_logistica(){
        $id_comite = Auth::user()->id_comite;
        return $id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10;
    }

    public function soy_de_comunicacion(){
        $id_comite = Auth::user()->id_comite;
        return $id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14;
    }

    function es_de_mi_comite($id_comite){

        if(!$this->soy_de_un_comite()){
            return false;
        }

        // si es de logística
        if($this->es_de_logistica($id_comite) && $this->soy_de_logistica()){
            return true;
        }

        // si es de comunicación
        if($this->es_de_comunicacion($id_comite) && $this->soy_de_comunicacion()){
            return true;
        }

        // si es de cualquier otro comité
        if($id_comite == Auth::user()->id_comite){
            return true;
        }

        // si no es de ningún comité, devuelve FALSE
        return false;
    }
}
