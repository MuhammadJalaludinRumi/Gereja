<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    protected $table = 'news';

    protected $fillable = [
        'date_post',
        'title',
        'content',
        'thumbnail',
        'image',
        'status',
        'show_on_login'
    ];

    public $timestamps = false;
}
