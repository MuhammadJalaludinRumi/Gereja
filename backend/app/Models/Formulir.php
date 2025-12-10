<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Formulir extends Model
{
    protected $fillable = [
        'email_pengguna',
        'pesan'
    ];
}
