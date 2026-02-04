<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Announcement extends Model
{
    protected $table = 'announcements';

    protected $fillable = [
        'title',
        'content',
        'date_post',
        'status',
        'organization_id',
    ];

    public $timestamps = false;
}
