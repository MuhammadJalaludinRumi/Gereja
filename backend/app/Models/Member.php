<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Member extends Model
{
    use HasFactory;

    protected $table = 'members';

    /**
     * Karena tabel tidak memiliki kolom created_at & updated_at
     */
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
        'attest_origin'
    ];

    // relasi ke city
    public function city()
    {
        return $this->belongsTo(City::class, 'city');
    }
}
