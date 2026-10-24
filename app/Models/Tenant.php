<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    protected $table = 'tenants';

    public $timestamps = false;

    protected $fillable = [
        'user_id',
        'room_id',
        'phone',
        'id_card',
        'move_in_date',
        'move_out_date',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
