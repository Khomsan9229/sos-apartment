<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Tenant;
use App\Models\RentBill;
use App\Models\Payment;
use App\Models\Repair;

class AdminController extends Controller
{
    public function index()
    {
        // ข้อมูลห้องพัก
        $totalRooms = Room::count();

        $availableRooms = Room::where('status', 'available')->count();

        $occupiedRooms = Room::where('status', 'occupied')->count();

        $repairRooms = Room::where('status', 'repair')->count();

        // ผู้เช่าที่กำลังพักอาศัย
        $totalTenants = Tenant::where('status', 'active')->count();

        // บิลค่าเช่า
        $unpaidBills = RentBill::where('status', 'unpaid')->count();

        $pendingBills = RentBill::where('status', 'pending')->count();

        $paidBills = RentBill::where('status', 'paid')->count();

        // การชำระเงิน
        $pendingPayments = Payment::where('status', 'pending')->count();

        $approvedPayments = Payment::where('status', 'approved')->count();

        // รายการแจ้งซ่อม
        $pendingRepairs = Repair::where('status', 'pending')->count();

        $inProgressRepairs = Repair::where('status', 'in_progress')->count();

        return view('admin.index', compact(
            'totalRooms',
            'availableRooms',
            'occupiedRooms',
            'repairRooms',
            'totalTenants',
            'unpaidBills',
            'pendingBills',
            'paidBills',
            'pendingPayments',
            'approvedPayments',
            'pendingRepairs',
            'inProgressRepairs'
        ));
    }
}
