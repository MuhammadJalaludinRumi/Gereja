<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrganizationLicense extends Model
{
    use HasFactory;

    protected $table = 'organization_licenses';

    protected $fillable = [
        'organization_id',
        'license_id',
        'max_member',
        'is_active',
        'expiry',
    ];

    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function license()
    {
        return $this->belongsTo(License::class);
    }
}
