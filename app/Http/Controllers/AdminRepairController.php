<?php

namespace App\Http\Controllers;

use App\Models\Repair;
use App\Models\Notification;
use Illuminate\Http\Request;

class AdminRepairController extends Controller
{
    // แสดงรายการแจ้งซ่อมทั้งหมด
    public function index()
    {
        $repairs = Repair::with([
            'tenant.user',
            'tenant.room'
        ])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.repairs.index', [
            'repairs' => $repairs
        ]);
    }

    // แสดงหน้าแก้ไขสถานะการแจ้งซ่อม
    public function edit(Repair $repair)
    {
        $repair->load([
            'tenant.user',
            'tenant.room'
        ]);

        return view('admin.repairs.edit', [
            'repair' => $repair
        ]);
    }

    // บันทึกสถานะและหมายเหตุจาก Admin

    public function update(Request $request, Repair $repair)
    {
        $validated = $request->validate([
            'status' => [
                'required',
                'in:pending,in_progress,completed'
            ],
            'admin_note' => [
                'nullable',
                'string',
                'max:2000'
            ],
        ]);

        // เก็บสถานะเดิมก่อนเปลี่ยน
        $oldStatus = $repair->status;

        // อัปเดตข้อมูลแจ้งซ่อม
        $repair->status = $validated['status'];
        $repair->admin_note = $validated['admin_note'] ?? null;
        $repair->save();

        // สร้างการแจ้งเตือนเฉพาะเมื่อสถานะเปลี่ยน
        if ($oldStatus !== $repair->status) {

            $statusText = match ($repair->status) {
                'pending' => 'รอดำเนินการ',
                'in_progress' => 'กำลังดำเนินการ',
                'completed' => 'ซ่อมเสร็จแล้ว',
            };

            // หาบัญชีผู้เช่าที่แจ้งซ่อม
            $repair->load('tenant');

            $userId = $repair->tenant?->user_id;

            if ($userId) {
                $notification = new Notification();

                $notification->user_id = $userId;
                $notification->title = 'อัปเดตสถานะการแจ้งซ่อม';
                $notification->message =
                    'รายการแจ้งซ่อม "' . $repair->title .
                    '" เปลี่ยนสถานะเป็น ' . $statusText;

                $notification->type = 'repair';
                $notification->is_read = 0;
                $notification->save();
            }
        }

        return redirect()
            ->route('admin.repairs.index')
            ->with('success', 'อัปเดตสถานะการแจ้งซ่อมเรียบร้อยแล้ว');
    }
}
