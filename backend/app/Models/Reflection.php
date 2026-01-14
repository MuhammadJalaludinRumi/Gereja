<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reflection extends Model
{
    protected $table = 'reflections';

    protected $fillable = [
        'title',
        'content',
        'date_post',
        'image',
        'status',
    ];

    public $timestamps = false;
}
