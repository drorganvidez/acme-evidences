<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class AccountController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('account.account', ['user' => $user]);
    }

    public function account_upload(Request $request){

        $user = Auth::user();

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255']
        ]);

        $user->name = $request->name;
        $user->surname = $request->surname;

        // Si el usuario cambia el uvus, comprueba que sea único
        if($request->uvus != $user->uvus) {
            $request->validate([
                'uvus' => ['required', 'string', 'max:255', 'unique:users']
            ]);
            $user->uvus = $request->uvus;
        }

        // si el usuario cambia el email, comprueba que el nuevo sea único

        if($request->email != $user->email){
            $request->validate([
                'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            ]);
            $user->email = $request->email;
        }

        // si cambia la contraseña, comprueba que sea única
        if($request->password != "" && $request->password_confirm != ""){
            $request->validate([
                'password' => 'min:8|required_with:password_confirm|same:password_confirm',
                'password_confirm' => 'min:8'
            ]);
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect("/account")->with('status', '¡Perfil actualizado!');
    }

    public function journeys()
    {
        $user = Auth::user();
        return view('account.journeys', ['user' => $user]);
    }

    public function journeys_upload(Request $request){

        $request->validate([
            'participation' => ['required', 'numeric', 'between:1,3'],
            'description' => ['required', 'min:50', 'max:20000']
        ]);

        $user = Auth::user();
        $user->journeys_participation = $request->participation;
        $user->journeys_description = $request->description;
        $user->save();

        return redirect("/account/journeys")->with('status', '¡Perfil de jornadas actualizado!');
    }
}
