<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Member extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'members';

    // kalau tabel emang nggak pake created_at/updated_at
    public $timestamps = false;

    protected $fillable = [
        'id_local',
        'name',
        'id_type',
        'id_number',
        'dob',
        'pob',
        'nationality',
        'ethnic',
        'sex',
        'phone',
        'email',
        'address',
        'city',
        'latitude',
        'longitude',
        'photo',
        'marriage',
        'is_deceased',
        'is_active',
        'family_id',
        'family_relation',
        'religion',
        'blood',
        'baptist_date',
        'baptist_place',
        'baptist_host_id',
        'baptist_host_name',
        'consecrate_date',
        'consecrate_place',
        'consecrate_host_id',
        'consecrate_host_name',
        'attest_date',
        'attest_origin',
        'family_group_id',
        'user_id',
    ];

    protected $casts = [
        'dob' => 'datetime',
        'baptist_date' => 'datetime',
        'consecrate_date' => 'datetime',
        'attest_date' => 'datetime',
        'is_deceased' => 'boolean',
        'is_active' => 'boolean',
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    /**
     * RELATION ke City.
     * Nama method = "city" supaya konsisten dengan OrganizationController
     * dan supaya frontend tetap bisa minta eager load with('city').
     *
     * Field FK di DB tetap bernama `city` (integer).
     */
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
