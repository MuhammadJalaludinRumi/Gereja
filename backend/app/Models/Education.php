<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'educations';

    protected $fillable = [
        'member',
        'level',
        'institution',
        'major',
        'year_graduate'
    ];

    public function memberData()
    {
        return $this->belongsTo(Member::class, 'member');
    }
}
