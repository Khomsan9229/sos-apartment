<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Repair extends Model
{
    protected $table = 'repairs';

    protected $fillable = [
        'tenant_id',
        'title',
        'description',
        'status',
        'admin_note',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
