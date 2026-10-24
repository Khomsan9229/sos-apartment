<?php

namespace App\Http\Controllers;

use App\Models\RentBill;
use App\Models\Tenant;
use Illuminate\Support\Facades\Auth;

class TenantBillController extends Controller
{
    public function index()
    {
        // ค้นหาข้อมูลผู้เช่าจากบัญชีที่ Login
        $tenant = Tenant::where('user_id', Auth::id())
            ->firstOrFail();

        // ดึงเฉพาะบิลของผู้เช่าคนนี้
        $bills = RentBill::where('tenant_id', $tenant->id)
            ->orderBy('id', 'desc')
            ->get();

        return view('tenant.bills.index', compact('bills'));
    }
}
