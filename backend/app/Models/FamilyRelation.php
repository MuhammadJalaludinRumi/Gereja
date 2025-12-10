<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FamilyRelation extends Model
{
    protected $table = 'family_relations';

    protected $fillable = [
        'member_id',
        'related_member_id',
        'related_auxiliary_id',
        'nik_manual',
        'nama_manual',
        'relation_type',
    ];

    /**
     * Member asal (si pemilik keluarga)
     */
    public function member()
    {
        return $this->belongsTo(Member::class, 'member_id');
    }

    /**
     * Jika relasi ini menunjuk ke anggota jemaat (members)
     */
    public function relatedMember()
    {
        return $this->belongsTo(Member::class, 'related_member_id');
    }

    /**
     * Jika relasi ini menunjuk ke auxiliary_persons (non anggota)
     */
    public function relatedAux()
    {
        return $this->belongsTo(AuxiliaryPerson::class, 'related_auxiliary_id');
    }

    /**
     * Getter untuk menampilkan nama final (prioritas: member > aux > manual)
     */
    public function getDisplayNameAttribute()
    {
        if ($this->relatedMember) {
            return $this->relatedMember->name;
        }

        if ($this->relatedAux) {
            return $this->relatedAux->name;
        }

        return $this->nama_manual ?? '-';
    }

    /**
     * Getter untuk menampilkan NIK final (member > aux > manual)
     */
    public function getDisplayNikAttribute()
    {
        if ($this->relatedMember) {
            return $this->relatedMember->id_number;
        }

        if ($this->relatedAux) {
            return $this->relatedAux->id_local;
        }

        return $this->nik_manual ?? '-';
    }
}
