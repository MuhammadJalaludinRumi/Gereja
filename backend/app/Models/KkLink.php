<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KkLink extends Model
{
    protected $fillable = [
        'nokk_main',
        'nokk_related',
    ];

    public $timestamps = false;
}
