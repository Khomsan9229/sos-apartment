<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * แสดงหน้า Login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * ตรวจสอบการเข้าสู่ระบบ
     */
    public function login(Request $request)
    {
        // ตรวจสอบข้อมูลที่กรอกเข้ามา
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        // ตรวจสอบ username และ password จากฐานข้อมูล
        if (Auth::attempt($credentials)) {

            // ป้องกัน Session Fixation
            $request->session()->regenerate();

            // ดึงข้อมูล User ที่ Login สำเร็จ
            $user = Auth::user();

            // ตรวจสอบ Role
            if ($user->role === 'admin') {
                return redirect('/admin');
            }

            if ($user->role === 'tenant') {
                return redirect('/tenant');
            }

            // ถ้า Role ไม่ตรงกับที่ระบบกำหนด
            Auth::logout();

            return back()->withErrors([
                'username' => 'ไม่พบสิทธิ์การเข้าใช้งานระบบ',
            ]);
        }

        // Username หรือ Password ไม่ถูกต้อง
        return back()->withErrors([
            'username' => 'ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง',
        ])->onlyInput('username');
    }
    /**
     * ออกจากระบบ
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
