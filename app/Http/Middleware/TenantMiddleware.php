<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TenantMiddleware
{
    /**
     * ตรวจสอบว่าผู้ใช้เข้าสู่ระบบและเป็น Tenant หรือไม่
     */
    public function handle(Request $request, Closure $next): Response
    {
        // ยังไม่ได้ Login
        if (!auth()->check()) {
            return redirect('/login');
        }

        // Login แล้วแต่ไม่ใช่ Tenant
        if (auth()->user()->role !== 'tenant') {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        return $next($request);
    }
}
