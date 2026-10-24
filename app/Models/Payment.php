<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $table = 'payments';

    public $timestamps = false;

    protected $fillable = [
        'bill_id',
        'amount',
        'payment_date',
        'slip_image',
        'status',
        'note',
    ];

    public function rentBill()
    {
        return $this->belongsTo(RentBill::class, 'bill_id');
    }
}
