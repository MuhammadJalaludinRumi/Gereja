<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Economy extends Model
{
    use HasFactory;

    protected $fillable = ['member', 'update', 'class'];

    public $timestamps = false;

    public function member()
    {
        return $this->belongsTo(Member::class, 'member');
    }
}
