<?php

namespace App\Http\Controllers;

use App\Proof;
use App\Evidence;
use App\Comite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\EvidenceExport;
use App\User;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Support\Facades\DB;

class EvidenceController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'hours' => ['required', 'number', 'min:0.5', 'max:100']
        ]);
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function new()
    {

        /*
           Puede acceder a todas las evidencias:
               - Cualquiera que no sea el administrador
       */
        $es_administrador = Auth::user()->is_administrator;
        if($es_administrador){
            return redirect("/home");
        }

        $comites = Comite::all();
        return view('evidences.new', ['comites' => $comites]);
    }

    public function list()
    {
        $id = Auth::user()->id;
        $evidences = DB::table('evidence')->where('id_user', $id)->orderBy('created_at', 'desc')->paginate(5);
        return view('evidences.list', ['evidences' => $evidences]);
    }

    public function all()
    {

        /*
            Puede acceder a todas las evidencias:
                - El administrador
        */
        $es_administrador = Auth::user()->is_administrator;
        if(!$es_administrador){
            return redirect("/home");
        }

        $evidences = DB::table('evidence')->orderBy('created_at', 'desc')->paginate(5);
        return view('evidences.list', ['evidences' => $evidences]);
    }

    public function comite(){

        /*
           Puede acceder a todas las evidencias:
               - El encargado de un comité
       */

        // MIDDLEWARE: SOLO PUEDEN ACCEDER LOS QUE TENGAN UN COMITÉ ASIGNADO O SEA ADMINISTRADOR
        if(!Auth::user()->is_comite){
            return redirect("/home");
        }

        $id_user_comite = Auth::user()->id_comite;
        $comite = Comite::find($id_user_comite);

        if($id_user_comite == 7 || $id_user_comite == 8 || $id_user_comite == 9 || $id_user_comite == 10){
            $evidences = DB::table('evidence')->whereBetween('id_comite',[7,10])->orderBy('created_at', 'desc')->paginate(5);
        }else if($id_user_comite == 11 || $id_user_comite == 12 || $id_user_comite == 13 || $id_user_comite == 14){
            $evidences = DB::table('evidence')->whereBetween('id_comite',[12,14])->orderBy('created_at', 'desc')->paginate(5);
        }else{
            $evidences = DB::table('evidence')->where('id_comite', $id_user_comite)->orderBy('created_at', 'desc')->paginate(5);
        }

        //$evidences = $evidences->sortByDesc('created_at');

        return view('evidences.list', ['evidences' => $evidences, 'comite' => $comite]);
    }

    public function export($id){

        /*
            Puede exportar a Excel:
                - El administrador
                - El responsable de ese comité
        */
        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($id);
        if(!$es_administrador && !$es_de_mi_comite){
            return redirect("/home");
        }

        return Excel::download(new EvidenceExport($id), 'evidencias.xlsx');
    }

    protected function create(Request $request)
    {

        $request->validate([
            'title' => ['required', 'min:5', 'max:255'],
            'hours' => ['required', 'numeric', 'between:0.5,99.99', 'max:100'],
            'comite' => ['required', 'numeric', 'min: 1', 'max:14'],
            'description' => ['required', 'min:10', 'max:20000']
        ]);

        // datos necesarios para crear evidencias
        $auth_user = Auth::user();

        // creación de una nueva evidencia
        $evidence = Evidence::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'hours' => $request->input('hours'),
            'reference' => '0123',
            'id_user' => $auth_user->id,
            'id_comite' => $request->input('comite')
        ]);

        // creación de la prueba o pruebas adjuntas
        $files = $request->file('files');
        foreach($files as $file){

            // almacenamos en disco la prueba
            $path = Storage::putFileAs('proofs/'.$auth_user->uvus.'/evidence_'.$evidence->id.'', $file, $file->getClientOriginalName());

            // almacenamos en la BBDD la información de la prueba
            $proof = Proof::create([
                'id_evidence' => $evidence->id,
                'file_name' => $file->getClientOriginalName(),
                'file_type' => $file->getClientOriginalExtension(),
                'file_route' => $path,
                'size' => $file->getClientSize(),
            ]);
        }

        return redirect('evidences/list');
        //return $this->list();

    }

    public function id($id){

        // obtención de la evidencia
        $evidence = Evidence::find($id);
        if($evidence ==null){
            return redirect("/home");
        }
        $id_comite = $evidence->id_comite;

        /*
            Puede acceder:
                - El administrador
                - El responsable del comité de esa evidencia
                - El propietario de la evidencia con ese $id
        */

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($id_comite);
        $es_mi_evidencia = Auth::user()->id == $evidence->id_user;

        if(!$es_administrador && !$es_de_mi_comite && !$es_mi_evidencia){
            return redirect("/home");
        }

        if($evidence !=null){
            // obtención de las pruebas
            $proofs = Proof::where('id_evidence', $id)->get();

            // obtención del comité
            $comite = Comite::find($evidence->id_comite);

            // obtención del usuario
            $user = User::find($evidence->id_user);

            // conversión del tamaño de los archivos a MB
            foreach($proofs as $proof){
                $proof->size = $proof->size/1000; // a KB
                $proof->size = $proof->size/1000; // a MB
                $proof->size = round($proof->size, 2); // redondeo a 2 decimales
            }

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

            return view('evidences.view', ['evidence' => $evidence, 'proofs' => $proofs, 'comite' => $comite, 'user' => $user, 'implicacion' => $implicacion]);
        }

        return $this->list();

    }

    public function delete($id){

        try {

            $auth_user = Auth::user();

            // obtención de la evidencia
            $evidence = Evidence::find($id);
            if($evidence ==null){
                return redirect("/home");
            }
            $id_comite = $evidence->id_comite;

            /*
                Puede borrar una evidencia:
                    - El administrador
                    - El responsable del comité de esa evidencia
                    - El propietario de la evidencia con ese $id
            */
            $es_administrador = Auth::user()->is_administrator;
            $es_de_mi_comite = $this->es_de_mi_comite($id_comite);
            $es_mi_evidencia = Auth::user()->id == $evidence->id_user;

            if(!$es_administrador && !$es_de_mi_comite && !$es_mi_evidencia){
                return redirect("/home");
            }

            if($evidence != null)
                $proofs = Proof::where('id_evidence', $evidence->id)->get();
                foreach($proofs as $proof){
                    $proof->delete();
                }
                Storage::deleteDirectory('proofs/'.$auth_user->uvus.'/evidence_'.$evidence->id.'');
                $evidence->delete();
        } catch (Exception $e) {

            echo $e;

        }

        if(Auth::user()->is_administrator){
            return redirect('evidences/all');
        }else{
            //return $this->list();
            return Redirect::back();
        }

    }

    public function check($id){

        // obtención de la evidencia
        $evidence = Evidence::find($id);
        if($evidence ==null){
            return redirect("/home");
        }

        /*
            Puede marcar una evidencia como BUENA/MALA:
                - El administrador
                - El responsable de ese comité
        */

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($evidence->id_comite);
        if(!$es_administrador && !$es_de_mi_comite){
            return Redirect::back();
        }

        // esto es un simple toggle
        if($evidence->check == 1){
            $evidence->check = 0;
        }else{
            $evidence->check = 1;
        }

        $evidence->save();

        return Redirect::back();
    }

    public function check_reject($id){

        // obtención de la evidencia
        $evidence = Evidence::find($id);
        if($evidence ==null){
            return redirect("/home");
        }

        /*
            Puede rechazar una evidencia:
                - El administrador
                - El responsable de ese comité
        */

        $es_administrador = Auth::user()->is_administrator;
        $es_de_mi_comite = $this->es_de_mi_comite($evidence->id_comite);
        if(!$es_administrador && !$es_de_mi_comite){
            return Redirect::back();
        }

        $evidence->check = 0;
        $evidence->save();

        return Redirect::back();

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
