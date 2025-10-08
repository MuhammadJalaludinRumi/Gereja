<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organization';

    protected $fillable = [
        'id',
        'name',
        'abbreviation',
        'address',
        'city',
        'latitude',
        'longitude',
        'phone',
        'email',
        'group_id',
        'website',
        'logo',
        'founded',
        'legal',
    ];

    public $timestamps = false;

    public function city()
{
    return $this->belongsTo(City::class, 'city');
}
}
