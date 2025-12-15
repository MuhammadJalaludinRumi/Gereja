<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AssetMovement extends Model
{
    use HasFactory;

    protected $table = 'asset_movements';

    public $timestamps = false;

    protected $fillable = [
        'asset_id',
        'from_location_id',
        'to_location_id',
        'moved_by',
        'moved_at',
        'notes',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }

    public function fromLocation()
    {
        return $this->belongsTo(Location::class, 'from_location_id');
    }

    public function toLocation()
    {
        return $this->belongsTo(Location::class, 'to_location_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'moved_by');
    }
}
