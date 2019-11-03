<?php

namespace App\Http\Controllers;

use App\Meeting;
use App\MeetingAttendee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AttendeeController extends Controller
{
    public function attendees()
    {

        $total_horas = 0;
        $meetings = collect();

        $id = Auth::user()->id;

        $meeting_list_attendees = MeetingAttendee::where('id_user', $id)->get();

        foreach($meeting_list_attendees as $meeting_list_attendee){
            $meeting = Meeting::find($meeting_list_attendee->id_meeting);
            $total_horas += $meeting->hours;
            $meetings->push($meeting);
        }

        return view('attendee.list', ['meetings' => $meetings, 'total_horas' => $total_horas]);
    }
}
