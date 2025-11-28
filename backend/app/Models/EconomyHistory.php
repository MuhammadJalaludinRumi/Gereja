<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EconomyHistory extends Model
{
    protected $table = 'economy_history';

    protected $fillable = [
        'economy_id',
        'old_class',
        'new_class',
        'updated_by'
    ];

    public $timestamps = true;
}
