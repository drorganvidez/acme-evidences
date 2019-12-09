<?php

namespace App\Exports;

use App\Evidence;
use App\MeetingAttendee;
use App\Meeting;
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

        // asistencia a reuniones
        $meeting_list_attendees = MeetingAttendee::all();

        $users = User::all();
        $res = collect();
        foreach($users as $user){

            if($user->uvus != "admin"){ // evitar incluir el admin en el excel

                // 1. Busco todas las evidencias de ese usuario y sumo sus horas
                $total_hours_evidences = 0;
                $user_evidences = collect();
                foreach($evidences as $evidence){
                    if($evidence->id_user == $user->id){
                        $user_evidences->push($evidence);
                        $total_hours_evidences += $evidence->hours;
                    }
                }

                // 2. Busco todas las asistencia a reuniones de ese usuario y sumo sus horas
                $total_attendees = 0;
                foreach($meeting_list_attendees as $meeting_list_attendee){
                    if($meeting_list_attendee->id_user == $user->id){
                        $meeting = Meeting::find($meeting_list_attendee->id_meeting);
                        $total_attendees += $meeting->hours;
                    }
                }

                // 3. Añado ese usuario al Excel
                if($total_hours_evidences != 0){

                    // Adjuntamos información básica del usuario
                    $res->push(
                        (object) [
                        'uvus' => $user->uvus,
                        'apellidos' => $user->surname,
                        'nombre' => $user->name,
                        'nivel_participacion' => $user->journeys_participation,
                        'horas_asistencia' => 0,
                        'horas_reuniones' => $total_attendees,
                        'horas_totales_evidencias' => $total_hours_evidences,
                        'evidencia_1' => isset($user_evidences[0]) ? $user_evidences[0]->hours : '',
                        'evidencia_1_url' => isset($user_evidences[0]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[0]->id : '',
                        'evidencia_2' => isset($user_evidences[1]) ? $user_evidences[1]->hours : '',
                        'evidencia_2_url' => isset($user_evidences[1]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[1]->id : '',
                        'evidencia_3' => isset($user_evidences[2]) ? $user_evidences[2]->hours : '',
                        'evidencia_3_url' => isset($user_evidences[2]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[2]->id : '',
                        'evidencia_4' => isset($user_evidences[3]) ? $user_evidences[3]->hours : '',
                        'evidencia_4_url' => isset($user_evidences[3]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[3]->id : '',
                        'evidencia_5' => isset($user_evidences[4]) ? $user_evidences[4]->hours : '',
                        'evidencia_5_url' => isset($user_evidences[4]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[4]->id : '',
                        'evidencia_6' => isset($user_evidences[5]) ? $user_evidences[5]->hours : '',
                        'evidencia_6_url' => isset($user_evidences[5]) ? 'https://www.acme-evidences.com/evidences/view/'.$user_evidences[5]->id : '',
                        'horas_total' => $total_attendees + $total_hours_evidences,
                        ]);

                }

            }

        }

        $res = $res->sortBy('apellidos')->sortBy('journeys_participation');
        return $res;
    }

    public function headings(): array
    {
        return [
            'uvus',
            'Apellidos',
            'Nombre',
            'Nivel de participación',
            'Horas de asistencia',
            'Horas de reuniones + bono comunicaciones',
            'Horas totales en evidencias',
            'Evidencia #1 (horas)',
            'Evidencia #1 (url)',
            'Evidencia #2 (horas)',
            'Evidencia #2 (url)',
            'Evidencia #3 (horas)',
            'Evidencia #3 (url)',
            'Evidencia #4 (horas)',
            'Evidencia #4 (url)',
            'Evidencia #5 (horas)',
            'Evidencia #5 (url)',
            'Evidencia #6 (horas)',
            'Evidencia #6 (url)',
            'Horas en total',
            'Nota'
        ];
    }
}
