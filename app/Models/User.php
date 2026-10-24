<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * ตารางที่ใช้ในฐานข้อมูล
     */
    protected $table = 'users';

    /**
     * ตาราง users ของโปรเจกต์มี created_at
     * แต่ไม่มี updated_at
     */
    const UPDATED_AT = null;

    /**
     * ฟิลด์ที่สามารถบันทึกข้อมูลได้
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'role',
    ];

    /**
     * ฟิลด์ที่ไม่ต้องการให้แสดงออกมาโดยตรง
     */
    protected $hidden = [
        'password',
    ];
}
