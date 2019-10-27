<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingAttendee extends Model
{
    protected $table = 'meetings_attendees';

    protected $fillable = ['id_meeting', 'id_user'];

    public $timestamps = false;
}
