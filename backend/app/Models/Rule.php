<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rule extends Model
{
    use HasFactory;

    protected $table = 'rules';

    protected $fillable = [
        'role_id',
        'acl_id',
        'permission',
    ];

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;

    /**
     * Relasi ke tabel ACL
     */
    public function acl()
    {
        return $this->belongsTo(Acl::class, 'acl_id');
    }

    /**
     * Relasi ke tabel Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }
}
