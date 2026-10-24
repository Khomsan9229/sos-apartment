<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RentBill extends Model
{
    protected $table = 'rent_bills';

    const UPDATED_AT = null;

    protected $fillable = [
        'tenant_id',
        'billing_month',
        'room_rent',
        'water_cost',
        'electric_cost',
        'other_cost',
        'total_amount',
        'due_date',
        'status',
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'bill_id');
    }
}
