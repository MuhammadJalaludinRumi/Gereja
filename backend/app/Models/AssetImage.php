<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetImage extends Model
{
    protected $table = 'asset_images';

    public $timestamps = false; // hanya ada created_at

    protected $fillable = [
        'asset_id',
        'image_path',
        'created_at'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
