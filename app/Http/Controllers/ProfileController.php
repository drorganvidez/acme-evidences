<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Evidence;
use App\User;
use App\Comite;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function id($id){

        /*
            Puede acceder:
                - El administrador
        */
        $es_administrador = Auth::user()->is_administrator;
        if(!$es_administrador){
            return redirect("/home");
        }

        $user = User::find($id);

        if($user == null){
            return redirect("/home");
        }

        $comite = Comite::find($user->id_comite);
        $evidences = Evidence::where('id_user', $user->id)->get();

        $implicacion = 0;
        if($user->journeys_participation == 1){
            $implicacion = "ORGANIZACIÓN";
        }

        if($user->journeys_participation == 2){
            $implicacion = "INTERMEDIO";
        }

        if($user->journeys_participation == 3){
            $implicacion = "ASISTENCIA";
        }

        return view('profile.view', ['user' => $user, 'evidences' => $evidences, 'implicacion' => $implicacion, 'comite' => $comite]);

    }
}
