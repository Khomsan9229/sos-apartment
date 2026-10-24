<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TenantRepairController extends Controller
{
    // แสดงรายการแจ้งซ่อมของผู้เช่า
    public function index()
    {
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        $repairs = Repair::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('tenant.repairs.index', [
            'repairs' => $repairs
        ]);
    }

    // แสดงหน้าแบบฟอร์มแจ้งซ่อม
    public function create()
    {
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        return view('tenant.repairs.create');
    }

    // บันทึกคำร้องแจ้งซ่อม
    public function store(Request $request)
    {
        // ค้นหาผู้เช่าที่เข้าสู่ระบบ
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        // ตรวจสอบข้อมูลจากแบบฟอร์ม
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string'],
        ]);

        // บันทึกลงตาราง repairs
        $repair = new Repair();

        $repair->tenant_id = $tenant->id;
        $repair->title = $validated['title'];
        $repair->description = $validated['description'];
        $repair->status = 'pending';
        $repair->admin_note = null;

        $repair->save();

        // กลับไปหน้ารายการแจ้งซ่อม
        return redirect()
            ->route('tenant.repairs.index')
            ->with('success', 'ส่งคำร้องแจ้งซ่อมเรียบร้อยแล้ว');
    }
}
