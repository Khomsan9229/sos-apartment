<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    // แสดงรายการห้องพักทั้งหมด
    public function index()
    {
        $rooms = Room::orderBy('room_number')->get();

        return view('admin.rooms.index', compact('rooms'));
    }

    // แสดงหน้าแบบฟอร์มเพิ่มห้องพัก
    public function create()
    {
        return view('admin.rooms.create');
    }

    // บันทึกข้อมูลห้องพักลงฐานข้อมูล
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number',
            'floor' => 'required|integer|min:1',
            'room_type' => 'required|in:Standard,Deluxe',
            'monthly_rent' => 'required|numeric|min:0',
            'water_rate' => 'required|numeric|min:0',
            'electric_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,repair',
            'description' => 'nullable|string',
        ]);

        Room::create($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'เพิ่มห้องพักสำเร็จ');
    }


    public function edit(Room $room)
    {
        return view('admin.rooms.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'room_number' => 'required|string|max:50|unique:rooms,room_number,' . $room->id,
            'floor' => 'required|integer|min:1',
            'room_type' => 'required|in:Standard,Deluxe',
            'monthly_rent' => 'required|numeric|min:0',
            'water_rate' => 'required|numeric|min:0',
            'electric_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,repair',
            'description' => 'nullable|string',
        ]);

        // ป้องกันการเปลี่ยนสถานะห้องที่มีผู้เช่าอยู่ให้เป็นห้องว่าง
        if (
            $room->tenants()->where('status', 'active')->exists()
            && $validated['status'] !== 'occupied'
        ) {
            return back()
                ->withErrors([
                    'status' => 'ห้องนี้มีผู้เช่าอยู่ ไม่สามารถเปลี่ยนสถานะเป็นว่างหรือปิดซ่อมได้',
                ])
                ->withInput();
        }

        // อัปเดตข้อมูลห้องพัก
        $room->update($validated);

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'แก้ไขข้อมูลห้องพักสำเร็จ');
    }


    public function destroy(Room $room)
    {
        // ตรวจสอบว่าห้องนี้มีประวัติผู้เช่าหรือไม่
        if ($room->tenants()->exists()) {
            return redirect()
                ->route('admin.rooms.index')
                ->with('error', 'ไม่สามารถลบห้องนี้ได้ เนื่องจากมีข้อมูลผู้เช่าเชื่อมโยงอยู่');
        }

        // ลบห้องพักที่ไม่มีข้อมูลผู้เช่า
        $room->delete();

        return redirect()
            ->route('admin.rooms.index')
            ->with('success', 'ลบห้องพักสำเร็จ');
    }
}
