<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthority extends Model
{
    use HasFactory;

    protected $table = 'user_authorities';

    protected $fillable = [
        'user',
        'role',
    ];

    // Matikan auto timestamps
    public $timestamps = false;
}
