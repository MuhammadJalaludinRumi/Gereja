<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuxiliaryPerson extends Model
{
    protected $table = 'auxiliary_persons';

    protected $fillable = [
        'id_local',
        'member_id',
        'name',
        'family_relation'
    ];

    public $timestamps = false;
}
