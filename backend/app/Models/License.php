<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    use HasFactory;

    protected $table = 'licenses';

    protected $fillable = [
        'name',
        'price',
    ];

    // Primary key auto increment (default)
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Nonaktifkan timestamps karena tabel tidak punya created_at & updated_at
    public $timestamps = false;
}
