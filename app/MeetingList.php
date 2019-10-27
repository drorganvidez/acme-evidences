<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingList extends Model
{
    protected $table = 'meeting_list';

    protected $fillable = ['id_list', 'id_user'];
}
