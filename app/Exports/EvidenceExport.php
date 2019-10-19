<?php

namespace App\Exports;

use App\Evidence;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\User;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class EvidenceExport implements FromCollection, WithHeadings, ShouldAutoSize
{

    public $comite = 0;

    public function __construct(int $id){
        $this->comite = $id;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {

        $comite = $this->comite;

        // si el $comite es 0, es que el administrador ha exportado todas las evidencias de la BBDD
        if($comite == 0){
            $evidences = Evidence::all();
        }else{
            // obtenemos todas las evidencias del comité en concreto
            if($comite == 7 || $comite == 8 || $comite == 9 || $comite == 10){
                $evidences = Evidence::where('id_comite',7)->get();
                $evidences = $evidences->concat(Evidence::where('id_comite',8)->get());
                $evidences = $evidences->concat(Evidence::where('id_comite',9)->get());
                $evidences = $evidences->concat(Evidence::where('id_comite',10)->get());
            }else if($comite == 11 || $comite == 12 || $comite == 13 || $comite == 14){
                $evidences = Evidence::where('id_comite',11)->get();
                $evidences = $evidences->concat(Evidence::where('id_comite',12)->get());
                $evidences = $evidences->concat(Evidence::where('id_comite',13)->get());
                $evidences = $evidences->concat(Evidence::where('id_comite',14)->get());
            }else{
                $evidences = Evidence::where('id_comite',$comite)->get();
            }
        }

        $users = User::all();
        $res = collect();
        foreach($users as $user){

            if($user->uvus != "admin"){ // evitar incluir el admin en el excel

                // 1. Busco todas las evidencias de ese usuario y sumo sus horas
                $total_hours = 0;
                $contador = 0;
                foreach($evidences as $evidence){
                    if($evidence->id_user == $user->id){
                        $contador++;
                        $total_hours += $evidence->hours;
                    }
                }

                // 2. Añado ese usuario al Excel
                if($total_hours != 0){

                    // 2.1 Adjuntamos información básica del usuario
                    $res->push(
                        (object) [
                        'uvus' => $user->uvus,
                        'apellidos' => $user->surname,
                        'nombre' => $user->name,
                        'horas_totales' => $total_hours,
                        'contador' => $contador
                        ]);

                    // 2.2 Añadimos enlaces PARA EL ADMINISTRADOR

                }

            }

        }

        $res = $res->sortBy('apellidos');
        return $res;
    }

    public function headings(): array
    {
        return [
            'uvus',
            'Apellidos',
            'Nombre',
            'Horas totales en evidencias',
            'Nº total de evidencias aportadas',
        ];
    }
}
