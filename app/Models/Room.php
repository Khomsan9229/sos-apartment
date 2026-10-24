<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    /**
     * ตารางที่ใช้ในฐานข้อมูล
     */
    protected $table = 'rooms';

    /**
     * ตาราง rooms มี created_at
     * แต่ไม่มี updated_at
     */
    const UPDATED_AT = null;

    /**
     * ฟิลด์ที่สามารถบันทึกข้อมูลได้
     */
    protected $fillable = [
        'room_number',
        'floor',
        'room_type',
        'monthly_rent',
        'water_rate',
        'electric_rate',
        'status',
        'description',
    ];
    
    public function tenants()
    {
        return $this->hasMany(Tenant::class);
    }
}
