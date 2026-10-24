<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;

class TenantNotificationController extends Controller
{
    // แสดงการแจ้งเตือนของผู้เช่า
    public function index()
    {
        $notifications = Notification::where('user_id', Auth::id())
            ->orderBy('id', 'desc')
            ->get();

        return view('tenant.notifications.index', [
            'notifications' => $notifications
        ]);
    }

    // เปลี่ยนสถานะการแจ้งเตือนเป็นอ่านแล้ว
    public function markAsRead($id)
    {
        // ค้นหาเฉพาะการแจ้งเตือนของผู้ใช้ที่เข้าสู่ระบบ
        $notification = Notification::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        // บันทึกสถานะอ่านแล้ว
        $notification->is_read = 1;
        $notification->save();

        return redirect()
            ->route('tenant.notifications.index')
            ->with('success', 'อ่านการแจ้งเตือนเรียบร้อยแล้ว');
    }
}
