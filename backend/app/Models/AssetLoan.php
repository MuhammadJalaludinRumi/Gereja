<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssetLoan extends Model
{
    protected $table = 'asset_loans';

    protected $fillable = [
        'asset_id',
        'borrower_name',
        'borrower_type',
        'loan_date',
        'expected_return_date',
        'actual_return_date',
        'status',
        'notes'
    ];

    public function asset(): BelongsTo
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
