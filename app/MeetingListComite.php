<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MeetingListComite extends Model
{
    protected $table = 'meeting_list_comite';
    protected $primaryKey = 'id_list';

    protected $fillable = ['id_list', 'id_comite', 'title'];
}
