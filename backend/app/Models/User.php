<?php
// app/Models/User.php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    // Disable automatic timestamps karena tidak ada updated_at
    public $timestamps = false;

    // Atau kalau mau pakai created_at saja:
    // const UPDATED_AT = null;

    protected $fillable = [
        'username',
        'password',
        'name',
        'last_login',
        'last_change',
        'is_active',
        'role_id',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'password' => 'hashed',
        'last_login' => 'datetime',
        'last_change' => 'datetime',
        'is_active' => 'boolean',
        'created_at' => 'datetime',
    ];

    // Scope untuk user aktif
    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    // Scope untuk superadmin
    public function scopeSuperAdmin($query)
    {
        return $query->where('role_id', 1);
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}