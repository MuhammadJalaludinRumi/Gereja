<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    protected $table = 'locations';

    protected $fillable = [
        'parent_id',
        'name',
        'description'
    ];

    public $timestamps = true;

    /**
     * Relasi ke parent (lokasi induk)
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    /**
     * Relasi ke children (sub-lokasi)
     */
    public function children(): HasMany
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    /**
     * Relasi ke assets
     */
    public function assets(): HasMany
    {
        return $this->hasMany(Asset::class, 'location_id');
    }
}
