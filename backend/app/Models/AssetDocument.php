<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AssetDocument extends Model
{
    protected $table = 'asset_documents';

    protected $fillable = [
        'asset_id',
        'type',
        'file_path',
        'description',
    ];

    // 🚀 Disable timestamps biar Laravel ga nyari created_at / updated_at
    public $timestamps = false;

    public function asset()
    {
        return $this->belongsTo(Asset::class, 'asset_id');
    }
}
