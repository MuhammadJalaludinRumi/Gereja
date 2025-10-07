<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Acl extends Model
{
    use HasFactory;

    protected $table = 'acls';

    protected $fillable = [
        'name',
    ];

    // Primary key
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Tidak ada timestamps (karena tabel tidak punya created_at & updated_at)
    public $timestamps = false;
}
