<?php

namespace App\Http\Controllers;

use App\Models\RentBill;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class RentBillController extends Controller
{
    public function index()
    {
        // ดึงข้อมูลบิลค่าเช่าพร้อมข้อมูลผู้เช่าและห้องพัก
        $bills = RentBill::with([
            'tenant.user',
            'tenant.room'
        ])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.rent_bills.index', compact('bills'));
    }

    public function create()
    {
        // ดึงเฉพาะผู้เช่าที่ยังพักอาศัยอยู่
        $tenants = \App\Models\Tenant::with(['user', 'room'])
            ->where('status', 'active')
            ->whereNotNull('room_id')
            ->orderBy('id')
            ->get();

        return view('admin.rent_bills.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        // ตรวจสอบข้อมูลจากแบบฟอร์ม
        $validated = $request->validate([
            'tenant_id' => 'required|integer|exists:tenants,id',
            'billing_month' => [
                'required',
                'date_format:Y-m',
            ],
            'room_rent' => 'required|numeric|min:0',
            'water_cost' => 'required|numeric|min:0',
            'electric_cost' => 'required|numeric|min:0',
            'other_cost' => 'required|numeric|min:0',
            'due_date' => 'nullable|date',
        ]);

        // ตรวจสอบว่าผู้เช่ายังพักอาศัยอยู่หรือไม่
        $tenant = Tenant::where('id', $validated['tenant_id'])
            ->where('status', 'active')
            ->whereNotNull('room_id')
            ->first();

        if (!$tenant) {
            throw ValidationException::withMessages([
                'tenant_id' => 'ไม่พบผู้เช่าที่กำลังพักอาศัย',
            ]);
        }

        // ตรวจสอบบิลซ้ำในเดือนเดียวกัน
        $existingBill = RentBill::where(
            'tenant_id',
            $validated['tenant_id']
        )
            ->where('billing_month', $validated['billing_month'])
            ->exists();

        if ($existingBill) {
            throw ValidationException::withMessages([
                'billing_month' => 'ผู้เช่าคนนี้มีบิลค่าเช่าสำหรับเดือนนี้แล้ว',
            ]);
        }

        // คำนวณยอดรวมฝั่ง Server
        $totalAmount =
            $validated['room_rent'] +
            $validated['water_cost'] +
            $validated['electric_cost'] +
            $validated['other_cost'];

        // บันทึกข้อมูลลงฐานข้อมูล
        RentBill::create([
            'tenant_id' => $validated['tenant_id'],
            'billing_month' => $validated['billing_month'],
            'room_rent' => $validated['room_rent'],
            'water_cost' => $validated['water_cost'],
            'electric_cost' => $validated['electric_cost'],
            'other_cost' => $validated['other_cost'],
            'total_amount' => round($totalAmount, 2),
            'due_date' => $validated['due_date'] ?? null,
            'status' => 'unpaid',
        ]);

        return redirect()
            ->route('admin.rent_bills.index')
            ->with('success', 'สร้างบิลค่าเช่าสำเร็จ');
    }

    public function show(RentBill $rentBill)
    {
        // ดึงข้อมูลผู้เช่าและห้องพัก
        $rentBill->load([
            'tenant.user',
            'tenant.room'
        ]);

        return view('admin.rent_bills.show', [
            'bill' => $rentBill
        ]);
    }
}
