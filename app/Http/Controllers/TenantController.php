<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class TenantController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลผู้เช่าพร้อมข้อมูลผู้ใช้และห้องพัก
        $tenants = Tenant::with(['user', 'room'])
            ->orderBy('id', 'desc')
            ->get();

        // ส่งข้อมูลไปยังหน้ารายชื่อผู้เช่า
        return view('admin.tenants.index', compact('tenants'));
    }

    public function create()
    {
        // ดึงเฉพาะห้องพักที่ว่าง
        $rooms = \App\Models\Room::where('status', 'available')
            ->orderBy('room_number')
            ->get();

        return view('admin.tenants.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลจากแบบฟอร์ม
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'id_card' => 'nullable|digits:13',
            'room_id' => 'required|integer|exists:rooms,id',
            'move_in_date' => 'required|date',
        ]);

        DB::transaction(function () use ($validated) {

            // ล็อกข้อมูลห้องเพื่อป้องกันการจองห้องซ้ำ
            $room = Room::whereKey($validated['room_id'])
                ->lockForUpdate()
                ->firstOrFail();

            if ($room->status !== 'available') {
                throw ValidationException::withMessages([
                    'room_id' => 'ห้องนี้ไม่ว่างแล้ว กรุณาเลือกห้องอื่น',
                ]);
            }

            // สร้างบัญชีผู้ใช้
            $user = User::create([
                'fullname' => $validated['fullname'],
                'username' => $validated['username'],
                'password' => Hash::make($validated['password']),
                'role' => 'tenant',
            ]);

            // สร้างข้อมูลผู้เช่า
            Tenant::create([
                'user_id' => $user->id,
                'room_id' => $room->id,
                'phone' => $validated['phone'] ?? null,
                'id_card' => $validated['id_card'] ?? null,
                'move_in_date' => $validated['move_in_date'],
                'status' => 'active',
            ]);

            // เปลี่ยนสถานะห้องเป็นมีผู้เช่า
            $room->update([
                'status' => 'occupied',
            ]);
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'เพิ่มผู้เช่าสำเร็จ');
    }

    public function edit(Tenant $tenant)
    {
        // ดึงข้อมูลบัญชีผู้ใช้และห้องพักเดิม
        $tenant->load(['user', 'room']);

        // ดึงห้องว่าง รวมถึงห้องเดิมของผู้เช่า
        $rooms = Room::where('status', 'available')
            ->when($tenant->room_id, function ($query) use ($tenant) {
                $query->orWhere('id', $tenant->room_id);
            })
            ->orderBy('room_number')
            ->get();

        return view('admin.tenants.edit', compact('tenant', 'rooms'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'fullname' => 'required|string|max:255',
            'username' => 'required|string|max:100|unique:users,username,' . $tenant->user_id,
            'phone' => 'nullable|string|max:20',
            'id_card' => 'nullable|digits:13',
            'room_id' => 'required|integer|exists:rooms,id',
            'move_in_date' => 'required|date',
            'status' => 'required|in:active,moved_out',
        ]);

        DB::transaction(function () use ($tenant, $validated) {

            // ล็อกข้อมูลผู้เช่าเพื่อป้องกันการแก้ไขพร้อมกัน
            $tenant = Tenant::whereKey($tenant->id)
                ->lockForUpdate()
                ->firstOrFail();

            $oldRoomId = $tenant->room_id;
            $newRoomId = (int) $validated['room_id'];
            $newStatus = $validated['status'];

            // ล็อกห้องที่เกี่ยวข้องตามลำดับ ID
            $roomIds = array_values(array_unique(
                array_filter([$oldRoomId, $newRoomId])
            ));

            sort($roomIds);

            $rooms = Room::whereIn('id', $roomIds)
                ->orderBy('id')
                ->lockForUpdate()
                ->get()
                ->keyBy('id');

            $newRoom = $rooms->get($newRoomId);

            // ตรวจสอบห้องใหม่เฉพาะกรณีย้ายห้องและยังพักอาศัย
            if ($newStatus === 'active' && $oldRoomId != $newRoomId) {
                if (!$newRoom || $newRoom->status !== 'available') {
                    throw ValidationException::withMessages([
                        'room_id' => 'ห้องที่เลือกไม่ว่าง กรุณาเลือกห้องอื่น',
                    ]);
                }
            }

            // ป้องกันการกลับมา active ในห้องที่ไม่ว่าง
            if ($newStatus === 'active' && $tenant->status !== 'active') {
                if (!$newRoom || $newRoom->status !== 'available') {
                    throw ValidationException::withMessages([
                        'room_id' => 'ห้องที่เลือกไม่ว่าง กรุณาเลือกห้องอื่น',
                    ]);
                }
            }

            // อัปเดตบัญชีผู้ใช้
            $tenant->user->update([
                'fullname' => $validated['fullname'],
                'username' => $validated['username'],
            ]);

            // อัปเดตข้อมูลผู้เช่า
            $tenant->update([
                'phone' => $validated['phone'] ?? null,
                'id_card' => $validated['id_card'] ?? null,
                'room_id' => $newStatus === 'moved_out' ? null : $newRoomId,
                'move_in_date' => $validated['move_in_date'],
                'move_out_date' => $newStatus === 'moved_out'
                    ? ($tenant->move_out_date ?? now()->toDateString())
                    : null,
                'status' => $newStatus,
            ]);

            // ตรวจสอบว่าห้องเดิมยังมีผู้เช่าคนอื่นพักอยู่หรือไม่
            if ($oldRoomId && ($oldRoomId != $newRoomId || $newStatus === 'moved_out')) {
                $hasActiveTenant = Tenant::where('room_id', $oldRoomId)
                    ->where('status', 'active')
                    ->exists();

                if (!$hasActiveTenant) {
                    $rooms->get($oldRoomId)?->update([
                        'status' => 'available',
                    ]);
                }
            }

            // ห้องใหม่มีผู้เช่าเข้าพัก
            if ($newStatus === 'active') {
                $newRoom->update([
                    'status' => 'occupied',
                ]);
            }
        });

        return redirect()
            ->route('admin.tenants.index')
            ->with('success', 'แก้ไขข้อมูลผู้เช่าสำเร็จ');
    }
}
