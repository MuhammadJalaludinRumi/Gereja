<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marriage extends Model
{
    use HasFactory;

    protected $fillable = [
        'bride', 'bride_name',
        'groom', 'groom_name',
        'date', 'venue',
        'priest', 'priest_name',
        'is_active'
    ];

    // RELASI MEMBER
    public function brideMember()
    {
        return $this->belongsTo(Member::class, 'bride');
    }

    public function groomMember()
    {
        return $this->belongsTo(Member::class, 'groom');
    }

    public function priestMember()
    {
        return $this->belongsTo(Member::class, 'priest');
    }
}
