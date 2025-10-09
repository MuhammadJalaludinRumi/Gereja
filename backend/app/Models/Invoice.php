<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $table = 'invoices';
    protected $fillable = [
        'organization_id',
        'date',
        'current_expiry',
        'new_expiry',
        'total'
    ];

    public $timestamps = false;

    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
}
