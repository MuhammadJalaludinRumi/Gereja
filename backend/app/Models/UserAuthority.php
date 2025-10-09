<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAuthority extends Model
{
    use HasFactory;

    protected $table = 'user_authorities';

    protected $fillable = [
        'user_id',
        'role_id',
    ];

    // Matikan auto timestamps
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
