<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Meeting;
use App\MeetingList;
use App\MeetingListComite;
use App\MeetingAttendee;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\DB;

class MeetingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function main()
    {
        /*
           Puede acceder a las reuniones:
               - El encargado de un comité
       */

        if (!Auth::user()->is_comite) {
            return redirect("/home");
        }


        return view('meetings.main');
    }

    public function lists()
    {

        /*
           Puede acceder a las reuniones:
               - El encargado de un comité
       */

        if (!Auth::user()->is_comite) {
            return redirect("/home");
        }

        $id_comite = Auth::user()->id_comite;

        $listas = collect();

        if ($id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10) {
            $listas = MeetingListComite::whereBetween('id_comite', [7, 10])->orderBy('created_at', 'desc')->get();
        } else if ($id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14) {
            $listas = MeetingListComite::whereBetween('id_comite', [11, 14])->orderBy('created_at', 'desc')->get();
        } else {
            $listas = MeetingListComite::where('id_comite', $id_comite)->orderBy('created_at', 'desc')->get();
        }

        return view('meetings.lists', ['listas' => $listas]);

    }

    public function ajax()
    {

        /*
           Puede cargar los usuarios del sistema
               - El encargado de un comité
       */

        if (!Auth::user()->is_comite) {
            return redirect("/home");
        }

        $users = User::where('is_administrator', 0)->orderBy('surname', 'asc')->get();

        $usuarios = collect();
        foreach ($users as $user) {
            $usuarios->push(['id' => $user->id, 'name' => $user->name, 'surname' => $user->surname]);
        }


        return $usuarios;

    }

    public function lists_create(Request $request)
    {

        $request->validate([
            'title' => ['required ', 'min:5', 'max:255'],
            'lista_usuarios' => ['required'],
        ]);

        $ids = explode(" ", $request->lista_usuarios);


        $id_comite = Auth::user()->id_comite;

        // 1. Creamos la lista asociada a un comité
        $meeting_list_comite = MeetingListComite::create([
            'id_comite' => $id_comite,
            'title' => $request->title
        ]);

        foreach ($ids as $id_user) {

            // 2. Añadimos usuarios a dicha lista
            MeetingList::create([
                'id_list' => $meeting_list_comite->id_list,
                'id_user' => $id_user
            ]);

        }

        return redirect('meetings/lists');


    }

    public function lists_update(Request $request)
    {

        // Actualizamos la información básica de la lista
        $meeting_list_comite = MeetingListComite::where('id_list', $request->id)->first();
        $meeting_list_comite->title = $request->title;
        $meeting_list_comite->save();

        // Actualizamos los usuarios asociada a esa lista
        $ids = explode(" ", $request->lista_usuarios);

        // Borramos todas las anteriores
        MeetingList::where('id_list', $meeting_list_comite->id_list)->delete();


        // Guardamos las nuevas
        foreach ($ids as $id_user) {

            // 2. Añadimos usuarios a dicha lista
            MeetingList::create([
                'id_list' => $meeting_list_comite->id_list,
                'id_user' => $id_user
            ]);

        }


        return redirect('meetings/lists');

    }

    public function new()
    {

        /*
           Puede acceder a las listas predefinidas
               - El administrador
               - El encargado de un comité
        */


        $id_comite = Auth::user()->id_comite;

        $es_administrador = Auth::user()->is_administrator;
        $es_de_un_comite = Auth::user()->is_comite;
        if (!$es_administrador && !$es_de_un_comite) {
            return redirect("/home");
        }

        $id_comite = Auth::user()->id_comite;

        $listas = collect();

        if ($id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10) {
            $listas = MeetingListComite::whereBetween('id_comite', [7, 10])->orderBy('created_at', 'desc')->get();
        } else if ($id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14) {
            $listas = MeetingListComite::whereBetween('id_comite', [11, 14])->orderBy('created_at', 'desc')->get();
        } else {
            $listas = MeetingListComite::where('id_comite', $id_comite)->orderBy('created_at', 'desc')->get();
        }

        return view('meetings.new', ['listas' => $listas]);

    }

    public function ids(Request $request)
    {

        //$id_comite = Auth::user()->id_comite;
        $listas = MeetingList::where('id_list', $request->id)->get();

        $ids = collect();
        foreach ($listas as $lista) {
            $user = User::find($lista->id_user);
            $ids->push(['id' => $user->id, 'name' => $user->name, 'surname' => $user->surname]);
        }

        return $ids;

    }

    public function create(Request $request)
    {

        $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'hours' => ['required', 'numeric', 'between:0.5,99.99', 'max:100'],
            'type' => ['required', 'numeric', 'min: 1', 'max:2'],
            'place' => ['required', 'min:5', 'max:255'],
            'datetime' => ['required'],
            'lista_usuarios' => ['required']
        ]);

        $id_comite = Auth::user()->id_comite;

        // 1. Guardamos la info de la reunión
        $meeting = Meeting::create([
            'id_comite' => $id_comite,
            'title' => $request->title,
            'hours' => $request->hours,
            'type' => $request->type,
            'place' => $request->place,
            'datetime' => $request->datetime
        ]);

        // 2. Guardamos todos los asistentes de esa reunión
        $ids = explode(" ", $request->lista_usuarios);

        foreach ($ids as $id_user) {

            // 2. Añadimos usuarios a dicha lista
            MeetingAttendee::create([
                'id_meeting' => $meeting->id,
                'id_user' => $id_user
            ]);

        }


        return redirect('meetings/list');

    }


    public function list()
    {

        /*
           Puede acceder a las listas predefinidas
               - El administrador
               - El encargado de un comité
       */


        $id_comite = Auth::user()->id_comite;

        $es_administrador = Auth::user()->is_administrator;
        $es_de_un_comite = Auth::user()->is_comite;
        if (!$es_administrador && !$es_de_un_comite) {
            return redirect("/home");
        }

        $meetings = collect();
        $meetings = collect();
        $meetings = collect();

        if ($id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10) {
            $meetings = DB::table('meetings')->whereBetween('id_comite', [7, 10])->orderBy('created_at', 'desc')->paginate(5);
        } else if ($id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14) {
            $meetings = DB::table('meetings')->whereBetween('id_comite', [11, 14])->orderBy('created_at', 'desc')->paginate(5);
        } else {
            $meetings = DB::table('meetings')->where('id_comite', $id_comite)->orderBy('created_at', 'desc')->paginate(5);
        }

        return view('meetings.list', ['meetings' => $meetings]);

    }

    public function lists_delete($id)
    {

        /*
           Puede borrar una lista predefinida:
               - El administrador
               - El encargado de un comité RELACIONADO con esa lista
       */


        $meeting_list_comite = MeetingListComite::where('id_list', $id)->first();
        if ($meeting_list_comite == null) {
            return redirect("/home");
        }

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($meeting_list_comite->id_comite);
        if (!$es_administrador && !$es_de_mi_comite) {
            return redirect("/home");
        }

        $meeting_list_comite->delete();

        // Borramos todos los usuarios asociados a esta lista
        MeetingList::where('id_list', $id)->delete();

        return redirect('meetings/lists');

    }

    public function list_delete($id)
    {

        /*
           Puede borrar a las reuniones:
               - El administrador
               - El encargado de un comité RELACIONADO con esa lista
       */

        $meeting = Meeting::find($id);
        if ($meeting == null) {
            return redirect("/home");
        }

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($meeting->id_comite);
        if (!$es_administrador && !$es_de_mi_comite) {
            return redirect("/home");
        }

        // 1. Borramos la reunión
        Meeting::find($id)->delete();

        // 2. Borramos las asistencias
        MeetingAttendee::where('id_meeting', $id)->delete();

        return redirect('meetings/list');

    }

    public function lists_edit($id)
    {

        /*
           Puede editar una lista predefinida
            - El administrador
            - El encargado de un comité RELACIONADO con esa lista
       */

        $list = MeetingListComite::where('id_list', $id)->first();
        if ($list == null) {
            return redirect("/home");
        }

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($list->id_comite);
        if (!$es_administrador && !$es_de_mi_comite) {
            return redirect("/home");
        }


        return view('meetings.lists', ['list' => $list]);

    }

    public function list_edit($id)
    {

        $meeting = Meeting::find($id);
        if ($meeting == null) {
            return redirect("/home");
        }

        /*
           Puede editar una reunión
            - El administrador
            - El encargado de un comité RELACIONADO con esa reunión
        */

        $id_comite = Auth::user()->id_comite;
        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($meeting->id_comite);
        if (!$es_administrador && !$es_de_mi_comite) {
            return redirect("/home");
        }

        $listas = collect();

        if ($id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10) {
            $listas = MeetingListComite::whereBetween('id_comite', [7, 10])->orderBy('created_at', 'desc')->get();
        } else if ($id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14) {
            $listas = MeetingListComite::whereBetween('id_comite', [11, 14])->orderBy('created_at', 'desc')->get();
        } else {
            $listas = MeetingListComite::where('id_comite', $id_comite)->orderBy('created_at', 'desc')->get();
        }

        return view('meetings.new', ['meeting' => $meeting, 'listas' => $listas]);

    }

    public function attendees($id)
    {

        // Obtenemos los ids asistentes
        $meeting_attendees = MeetingAttendee::where('id_meeting', $id)->get();

        // Obtenemos los usuarios a partir de esos ids

        $users = collect();

        foreach ($meeting_attendees as $meeting_attendee) {
            $user = User::find($meeting_attendee->id_user);
            $users->push($user);
        }

        $usuarios = collect();

        foreach ($users as $user) {
            $usuarios->push(['id' => $user->id, 'name' => $user->name, 'surname' => $user->surname]);
        }

        return $usuarios;

    }

    public function attendee_update(Request $request)
    {

        $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'hours' => ['required', 'numeric', 'between:0.5,99.99', 'max:100'],
            'type' => ['required', 'numeric', 'min: 1', 'max:2'],
            'lista_usuarios' => ['required']
        ]);

        // 1. Actualizamos la info de la reunión
        $meeting = Meeting::find($request->id);
        $meeting->title = $request->title;
        $meeting->hours = $request->hours;
        $meeting->type = $request->type;
        $meeting->place = $request->place;
        $meeting->datetime = $request->datetime;
        $meeting->save();


        // 2. Actualizamos todos los asistentes de esa reunión
        $ids = explode(" ", $request->lista_usuarios);

        MeetingAttendee::where('id_meeting', $request->id)->delete();

        foreach ($ids as $id_user) {

            // 2. Añadimos usuarios a dicha lista
            MeetingAttendee::create([
                'id_meeting' => $meeting->id,
                'id_user' => $id_user
            ]);

        }


        return redirect('meetings/list');
    }

    public function es_de_logistica($id_comite)
    {
        return $id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10;
    }

    public function es_de_comunicacion($id_comite)
    {
        return $id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14;
    }

    public function soy_de_un_comite()
    {
        return Auth::user()->is_comite;
    }

    public function soy_de_logistica()
    {
        $id_comite = Auth::user()->id_comite;
        return $id_comite == 7 || $id_comite == 8 || $id_comite == 9 || $id_comite == 10;
    }

    public function soy_de_comunicacion()
    {
        $id_comite = Auth::user()->id_comite;
        return $id_comite == 11 || $id_comite == 12 || $id_comite == 13 || $id_comite == 14;
    }

    function es_de_mi_comite($id_comite)
    {

        if (!$this->soy_de_un_comite()) {
            return false;
        }

        // si es de logística
        if ($this->es_de_logistica($id_comite) && $this->soy_de_logistica()) {
            return true;
        }

        // si es de comunicación
        if ($this->es_de_comunicacion($id_comite) && $this->soy_de_comunicacion()) {
            return true;
        }

        // si es de cualquier otro comité
        if ($id_comite == Auth::user()->id_comite) {
            return true;
        }

        // si no es de ningún comité, devuelve FALSE
        return false;
    }
}
