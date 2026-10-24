<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentBill;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class TenantPaymentController extends Controller
{
    // แสดงหน้าอัปโหลดสลิป
    public function create(RentBill $rentBill)
    {
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        if ((int) $rentBill->tenant_id !== (int) $tenant->id) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงบิลนี้');
        }

        if ($rentBill->status !== 'unpaid') {
            return redirect()
                ->route('tenant.bills.index')
                ->with('error', 'บิลนี้ไม่สามารถส่งหลักฐานได้');
        }

        return view('tenant.payments.create', [
            'bill' => $rentBill
        ]);
    }

    // บันทึกหลักฐานการชำระเงิน
    public function store(Request $request, RentBill $rentBill)
    {
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        // ตรวจสอบเจ้าของบิล
        if ((int) $rentBill->tenant_id !== (int) $tenant->id) {
            abort(403, 'คุณไม่มีสิทธิ์ชำระบิลนี้');
        }

        // ตรวจสอบข้อมูลที่ส่งมา
        $validated = $request->validate([
            'amount' => ['required', 'numeric', 'gt:0', 'decimal:0,2'],
            'slip_image' => [
                'required',
                'image',
                'mimes:jpg,jpeg,png',
                'max:5120'
            ],
        ]);

        // ตรวจสอบยอดเงินให้ตรงกับบิล
        if (
            number_format((float) $validated['amount'], 2, '.', '')
            !== number_format((float) $rentBill->total_amount, 2, '.', '')
        ) {
            throw ValidationException::withMessages([
                'amount' => 'จำนวนเงินที่ชำระต้องตรงกับยอดบิล'
            ]);
        }

        // ตรวจสอบสถานะล่าสุดก่อนบันทึก
        $bill = DB::transaction(function () use ($rentBill) {
            $bill = RentBill::whereKey($rentBill->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($bill->status !== 'unpaid') {
                throw ValidationException::withMessages([
                    'payment' => 'บิลนี้ไม่สามารถส่งหลักฐานได้'
                ]);
            }

            return $bill;
        });

        // เก็บไฟล์สลิปใน storage/app/public/slips
        $slipPath = $request->file('slip_image')
            ->store('slips', 'public');

        try {
            DB::transaction(function () use (
                $bill,
                $validated,
                $slipPath
            ) {
                $lockedBill = RentBill::whereKey($bill->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($lockedBill->status !== 'unpaid') {
                    throw ValidationException::withMessages([
                        'payment' => 'บิลนี้มีการส่งหลักฐานแล้ว'
                    ]);
                }

                // สร้างรายการชำระเงิน
                Payment::create([
                    'bill_id' => $lockedBill->id,
                    'amount' => $validated['amount'],
                    'slip_image' => $slipPath,
                    'status' => 'pending',
                ]);

                // เปลี่ยนสถานะบิลเป็นรอตรวจสอบ
                $lockedBill->update([
                    'status' => 'pending',
                ]);
            });
        } catch (\Throwable $e) {
            // ลบไฟล์ที่อัปโหลด หากบันทึกฐานข้อมูลไม่สำเร็จ
            \Illuminate\Support\Facades\Storage::disk('public')
                ->delete($slipPath);

            throw $e;
        }

        return redirect()
            ->route('tenant.bills.index')
            ->with('success', 'ส่งหลักฐานการชำระเงินเรียบร้อยแล้ว');
    }

    public function history()
    {
        // ค้นหาผู้เช่าที่เข้าสู่ระบบ
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        // ดึงประวัติการชำระเงินเฉพาะบิลของผู้เช่าคนนี้
        $payments = Payment::with('rentBill')
            ->whereHas('rentBill', function ($query) use ($tenant) {
                $query->where('tenant_id', $tenant->id);
            })
            ->orderBy('id', 'desc')
            ->get();

        return view('tenant.payments.history', [
            'payments' => $payments
        ]);
    }
}
