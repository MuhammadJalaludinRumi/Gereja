<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    use HasFactory;

    protected $fillable = [
        'member',
        'company',
        'position',
        'year_start',
        'year_end'
    ];

    public $timestamps = false;

    // Relasi ke member
    public function memberData()
    {
        return $this->belongsTo(Member::class, 'member')->withTrashed();
    }
}
