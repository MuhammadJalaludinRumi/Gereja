<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Province extends Model
{
    protected $table = 'province'; // sesuaikan dengan nama table

    protected $fillable = [
        'name'
    ];

    public $timestamps = false; // kalau table tidak ada created_at / updated_at
}
