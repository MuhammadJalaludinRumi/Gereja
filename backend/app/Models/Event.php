<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $table = 'events';

    public $timestamps = false;

    protected $fillable = [
        'service_type',
        'service_date',
        'service_time',
        'service_ministry',
        'organization_id',
        'scripture_reading',
        'sermon_text',
        'sermon_theme',
        'coordinator',
        'liturgist',
        'pf_assistant',
        'musician',
        'worship_leader',
        'offering_officer',
        'choir',
        'male_attendance',
        'female_attendance',
        'total_attendance',
        'red_envelope',
        'blue_envelope',
        'other_envelope',
        'total_envelope',
        'note',
    ];

    protected $casts = [
        'musician' => 'array',
        'worship_leader' => 'array',
        'offering_officer' => 'array',
        'choir' => 'array',
        'service_date' => 'date',
    ];

    protected $guarded = ['id'];
}
