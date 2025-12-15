<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Asset extends Model
{
    protected $table = 'assets';

    protected $fillable = [
        'asset_code',
        'name',
        'category_id',
        'location_id',
        'purchase_date',
        'purchase_price',
        'condition',
        'status',
        'vendor',
        'notes',
        'created_by',
        'updated_by'
    ];

    public $timestamps = true;

    public function category(): BelongsTo
    {
        return $this->belongsTo(AssetCategory::class, 'category_id');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    public function loans()
    {
        return $this->hasMany(AssetLoan::class, 'asset_id');
    }
}
