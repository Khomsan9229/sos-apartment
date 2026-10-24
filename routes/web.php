<?php

use App\Http\Controllers\RoomController;
use App\Http\Controllers\TenantController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\RentBillController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\TenantBillController;
use App\Http\Controllers\TenantPaymentController;
use App\Http\Controllers\TenantRepairController;
use App\Http\Controllers\AdminRepairController;
use App\Http\Controllers\TenantNotificationController;
use App\Http\Controllers\ExpenseController;
use App\Http\Controllers\ReportController;


Route::get('/', function () {
    return view('welcome');
});

Route::get('/test-db', function () {
    $count = DB::table('users')->count();

    return "เชื่อมต่อฐานข้อมูลสำเร็จ<br>มีผู้ใช้ทั้งหมด: {$count} คน";
});

use App\Models\User;

Route::get('/test-user', function () {
    $user = User::first();

    return [
        'id' => $user->id,
        'username' => $user->username,
        'fullname' => $user->fullname,
        'role' => $user->role,
    ];
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

use App\Http\Controllers\AdminController;

Route::get('/admin', [AdminController::class, 'index'])
    ->middleware('admin');

Route::get('/admin/rooms', [RoomController::class, 'index'])
    ->middleware('admin')
    ->name('admin.rooms.index');

// เปิดหน้าเพิ่มห้องพัก
Route::get('/admin/rooms/create', [RoomController::class, 'create'])
    ->middleware('admin')
    ->name('admin.rooms.create');

// บันทึกข้อมูลห้องพัก
Route::post('/admin/rooms', [RoomController::class, 'store'])
    ->middleware('admin')
    ->name('admin.rooms.store');

// เปิดหน้าแก้ไขข้อมูลห้องพัก
Route::get('/admin/rooms/{room}/edit', [RoomController::class, 'edit'])
    ->middleware('admin')
    ->name('admin.rooms.edit');

// บันทึกการแก้ไขข้อมูลห้องพัก
Route::put('/admin/rooms/{room}', [RoomController::class, 'update'])
    ->middleware('admin')
    ->name('admin.rooms.update');

// ลบข้อมูลห้องพัก
Route::delete('/admin/rooms/{room}', [RoomController::class, 'destroy'])
    ->middleware('admin')
    ->name('admin.rooms.destroy');


// แสดงรายการผู้เช่าทั้งหมด
Route::get('/admin/tenants', [TenantController::class, 'index'])
    ->middleware('admin')
    ->name('admin.tenants.index');


// เปิดหน้าเพิ่มผู้เช่า
Route::get('/admin/tenants/create', [TenantController::class, 'create'])
    ->middleware('admin')
    ->name('admin.tenants.create');

// บันทึกข้อมูลผู้เช่าใหม่
Route::post('/admin/tenants', [TenantController::class, 'store'])
    ->middleware('admin')
    ->name('admin.tenants.store');


// เปิดหน้าแก้ไขข้อมูลผู้เช่า
Route::get('/admin/tenants/{tenant}/edit', [TenantController::class, 'edit'])
    ->middleware('admin')
    ->name('admin.tenants.edit');


Route::put('/admin/tenants/{tenant}', [TenantController::class, 'update'])
    ->middleware('admin')
    ->name('admin.tenants.update');


Route::get('/admin/rent-bills', [RentBillController::class, 'index'])
    ->middleware('admin')
    ->name('admin.rent_bills.index');


Route::get('/admin/rent-bills/create', [RentBillController::class, 'create'])
    ->middleware('admin')
    ->name('admin.rent_bills.create');


Route::post('/admin/rent-bills', [RentBillController::class, 'store'])
    ->middleware('admin')
    ->name('admin.rent_bills.store');


Route::get('/admin/rent-bills/{rentBill}', [RentBillController::class, 'show'])
    ->middleware('admin')
    ->name('admin.rent_bills.show');


Route::get('/admin/payments', [PaymentController::class, 'index'])
    ->middleware('admin')
    ->name('admin.payments.index');


Route::post('/admin/payments/{payment}/approve', [PaymentController::class, 'approve'])
    ->middleware('admin')
    ->name('admin.payments.approve');

Route::post('/admin/payments/{payment}/reject', [PaymentController::class, 'reject'])
    ->middleware('admin')
    ->name('admin.payments.reject');


Route::get('/tenant/bills', [TenantBillController::class, 'index'])
    ->middleware('tenant')
    ->name('tenant.bills.index');


Route::get('/tenant', function () {
    return view('tenant.index');
})->middleware('tenant')->name('tenant.dashboard');


Route::post(
    '/tenant/bills/{rentBill}/payment',
    [TenantPaymentController::class, 'store']
)
    ->middleware('tenant')
    ->name('tenant.payments.store');


Route::get('/tenant/bills/{rentBill}/payment', [TenantPaymentController::class, 'create'])
    ->middleware('tenant')
    ->name('tenant.payments.create');


Route::get('/tenant/payments/history', [TenantPaymentController::class, 'history'])
    ->middleware('tenant')
    ->name('tenant.payments.history');


Route::get('/tenant/repairs', [TenantRepairController::class, 'index'])
    ->middleware('tenant')
    ->name('tenant.repairs.index');


Route::get('/tenant/repairs/create', [TenantRepairController::class, 'create'])
    ->middleware('tenant')
    ->name('tenant.repairs.create');


Route::post('/tenant/repairs', [TenantRepairController::class, 'store'])
    ->middleware('tenant')
    ->name('tenant.repairs.store');


Route::get('/admin/repairs', [AdminRepairController::class, 'index'])
    ->middleware('admin')
    ->name('admin.repairs.index');

Route::get('/admin/repairs', [AdminRepairController::class, 'index'])
    ->middleware('admin')
    ->name('admin.repairs.index');


Route::get('/admin/repairs/{repair}/edit', [AdminRepairController::class, 'edit'])
    ->middleware('admin')
    ->name('admin.repairs.edit');

Route::put('/admin/repairs/{repair}', [AdminRepairController::class, 'update'])
    ->middleware('admin')
    ->name('admin.repairs.update');


Route::get('/tenant/notifications', [TenantNotificationController::class, 'index'])
    ->middleware('tenant')
    ->name('tenant.notifications.index');


Route::post('/tenant/notifications/{id}/read', [TenantNotificationController::class, 'markAsRead'])
    ->middleware('tenant')
    ->name('tenant.notifications.read');


Route::get('/admin/expenses', [ExpenseController::class, 'index'])
    ->middleware('admin')
    ->name('admin.expenses.index');


Route::get('/admin/expenses/create', [ExpenseController::class, 'create'])
    ->middleware('admin')
    ->name('admin.expenses.create');

Route::post('/admin/expenses', [ExpenseController::class, 'store'])
    ->middleware('admin')
    ->name('admin.expenses.store');


Route::get('/admin/expenses/{expense}/edit', [ExpenseController::class, 'edit'])
    ->middleware('admin')
    ->name('admin.expenses.edit');

Route::put('/admin/expenses/{expense}', [ExpenseController::class, 'update'])
    ->middleware('admin')
    ->name('admin.expenses.update');


Route::delete('/admin/expenses/{expense}', [ExpenseController::class, 'destroy'])
    ->middleware('admin')
    ->name('admin.expenses.destroy');

Route::get('/admin/reports', [ReportController::class, 'index'])
    ->middleware('admin')
    ->name('admin.reports.index');
