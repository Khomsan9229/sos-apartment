<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    // แสดงรายการค่าใช้จ่ายทั้งหมด
    public function index()
    {
        $expenses = Expense::orderBy('expense_date', 'desc')
            ->orderBy('id', 'desc')
            ->get();

        $totalExpenses = $expenses->sum('amount');

        return view('admin.expenses.index', [
            'expenses' => $expenses,
            'totalExpenses' => $totalExpenses
        ]);
    }

    // แสดงฟอร์มเพิ่มค่าใช้จ่าย
    public function create()
    {
        return view('admin.expenses.create');
    }

    // บันทึกค่าใช้จ่ายใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
        ]);

        $expense = new Expense();

        $expense->title = $validated['title'];
        $expense->description = $validated['description'] ?? null;
        $expense->amount = $validated['amount'];
        $expense->expense_date = $validated['expense_date'];
        $expense->category = $validated['category'];

        $expense->save();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'เพิ่มรายการค่าใช้จ่ายเรียบร้อยแล้ว');
    }

    // แสดงฟอร์มแก้ไขค่าใช้จ่าย
    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    // บันทึกข้อมูลที่แก้ไข
    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'amount' => 'required|numeric|min:0.01',
            'expense_date' => 'required|date',
            'category' => 'required|string|max:100',
        ]);

        $expense->title = $validated['title'];
        $expense->description = $validated['description'] ?? null;
        $expense->amount = $validated['amount'];
        $expense->expense_date = $validated['expense_date'];
        $expense->category = $validated['category'];

        $expense->save();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'แก้ไขรายการค่าใช้จ่ายเรียบร้อยแล้ว');
    }

    // ลบรายการค่าใช้จ่าย
    public function destroy(Expense $expense)
    {
        $expense->delete();

        return redirect()
            ->route('admin.expenses.index')
            ->with('success', 'ลบรายการค่าใช้จ่ายเรียบร้อยแล้ว');
    }
}
