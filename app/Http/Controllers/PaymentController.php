<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\RentBill;
use App\Models\Notification;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class PaymentController extends Controller
{
    // แสดงรายการชำระเงินทั้งหมด
    public function index()
    {
        $payments = Payment::with([
            'rentBill.tenant.user',
            'rentBill.tenant.room'
        ])
            ->orderBy('id', 'desc')
            ->get();

        return view('admin.payments.index', compact('payments'));
    }

    // อนุมัติการชำระเงิน
    public function approve(Payment $payment)
    {
        DB::transaction(function () use ($payment) {

            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // อนุมัติได้เฉพาะรายการที่รอตรวจสอบ
            if ($payment->status !== 'pending') {
                return;
            }

            $bill = RentBill::whereKey($payment->bill_id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($bill->status === 'paid') {
                return;
            }

            // ตรวจสอบยอดเงินก่อนอนุมัติ
            if (
                round((float) $payment->amount, 2)
                !== round((float) $bill->total_amount, 2)
            ) {
                throw ValidationException::withMessages([
                    'payment' => 'ยอดเงินที่ชำระไม่ตรงกับยอดบิล',
                ]);
            }

            // อัปเดตสถานะการชำระเงิน
            $payment->update([
                'status' => 'approved',
            ]);

            // อัปเดตสถานะบิล
            $bill->update([
                'status' => 'paid',
            ]);

            // สร้างการแจ้งเตือนให้ผู้เช่า
            $bill->load('tenant');

            $userId = $bill->tenant?->user_id;

            if ($userId) {
                $notification = new Notification();

                $notification->user_id = $userId;
                $notification->title = 'อนุมัติการชำระเงินแล้ว';
                $notification->message =
                    'การชำระเงินสำหรับบิลเดือน ' .
                    $bill->billing_month .
                    ' จำนวน ' .
                    number_format((float) $payment->amount, 2) .
                    ' บาท ได้รับการอนุมัติเรียบร้อยแล้ว';

                $notification->type = 'payment';
                $notification->is_read = 0;

                $notification->save();
            }
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'ดำเนินการอนุมัติเรียบร้อย');
    }

    // ไม่อนุมัติการชำระเงิน
    public function reject(Payment $payment)
    {
        DB::transaction(function () use ($payment) {

            $payment = Payment::whereKey($payment->id)
                ->lockForUpdate()
                ->firstOrFail();

            // ปฏิเสธได้เฉพาะรายการที่รอตรวจสอบ
            if ($payment->status !== 'pending') {
                return;
            }

            // เปลี่ยนสถานะการชำระเงิน
            $payment->update([
                'status' => 'rejected',
            ]);

            $bill = RentBill::whereKey($payment->bill_id)
                ->lockForUpdate()
                ->firstOrFail();

            // เปลี่ยนสถานะบิลกลับเป็นยังไม่ชำระ
            if ($bill->status !== 'paid') {
                $bill->update([
                    'status' => 'unpaid',
                ]);
            }

            // สร้างการแจ้งเตือนให้ผู้เช่า
            $bill->load('tenant');

            $userId = $bill->tenant?->user_id;

            if ($userId) {
                $notification = new Notification();

                $notification->user_id = $userId;
                $notification->title = 'การชำระเงินไม่ได้รับการอนุมัติ';
                $notification->message =
                    'การชำระเงินสำหรับบิลเดือน ' .
                    $bill->billing_month .
                    ' จำนวน ' .
                    number_format((float) $payment->amount, 2) .
                    ' บาท ไม่ได้รับการอนุมัติ กรุณาตรวจสอบข้อมูลการชำระเงิน';

                $notification->type = 'payment';
                $notification->is_read = 0;

                $notification->save();
            }
        });

        return redirect()
            ->route('admin.payments.index')
            ->with('success', 'ดำเนินการไม่อนุมัติเรียบร้อย');
    }
}
