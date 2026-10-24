<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Expense;
use App\Models\RentBill;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'month' => 'nullable|integer|between:1,12',
            'year' => 'nullable|integer|between:2000,2100',
        ]);

        $month = (int) $request->input('month', now()->month);
        $year = (int) $request->input('year', now()->year);

        $startDate = Carbon::create($year, $month, 1)->startOfMonth();
        $endDate = $startDate->copy()->endOfMonth();

        // รายรับ: เงินที่ได้รับอนุมัติในรายการชำระเงิน
        $totalIncome = Payment::where('status', 'approved')
            ->whereBetween('payment_date', [$startDate, $endDate])
            ->sum('amount');

        // รายจ่ายของเดือนที่เลือก
        $totalExpenses = Expense::whereBetween(
            'expense_date',
            [$startDate->toDateString(), $endDate->toDateString()]
        )->sum('amount');

        // ผลต่างรายรับ - รายจ่าย
        $balance = $totalIncome - $totalExpenses;

        // บิลค้างชำระของเดือนที่เลือก
        $totalUnpaid = RentBill::where('status', 'unpaid')
            ->where('billing_month', sprintf('%04d-%02d', $year, $month))
            ->sum('total_amount');


        $chartLabels = [];
        $chartIncome = [];
        $chartExpenses = [];

        for ($day = 1; $day <= $startDate->daysInMonth; $day++) {
            $date = sprintf('%04d-%02d-%02d', $year, $month, $day);

            $chartLabels[] = (string) $day;

            $chartIncome[] = (float) Payment::where('status', 'approved')
                ->whereDate('payment_date', $date)
                ->sum('amount');

            $chartExpenses[] = (float) Expense::whereDate('expense_date', $date)
                ->sum('amount');
        }


        return view('admin.reports.index', compact(
            'totalIncome',
            'totalExpenses',
            'balance',
            'totalUnpaid',
            'month',
            'year',
            'chartLabels',
            'chartIncome',
            'chartExpenses'
        ));
    }
}
