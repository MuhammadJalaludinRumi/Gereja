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
        'website',
        'logo',
        'founded',
        'legal',
    ];

    // Supaya primary key bukan auto increment
    public $incrementing = false;
    protected $keyType = 'string';
}
