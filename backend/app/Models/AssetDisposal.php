<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDisposal extends Model
{
    protected $table = 'asset_disposals';

    protected $fillable = [
        'asset_id',
        'disposal_date',
        'disposal_type',
        'value',
        'notes',
    ];

    protected $casts = [
        'disposal_date' => 'date',
        'value' => 'decimal:2',
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
