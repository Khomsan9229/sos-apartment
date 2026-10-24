<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard ผู้เช่า - SOS อพาร์ตเมนต์</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    @php
    // จำนวนแจ้งเตือนที่ยังไม่ได้อ่าน
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
    ->where('is_read', 0)
    ->count();

    // ข้อมูลผู้เช่าที่เข้าสู่ระบบ
    $tenant = \App\Models\Tenant::with('room')
    ->where('user_id', auth()->id())
    ->first();

    // บิลที่ยังไม่ชำระเสร็จ
    $unpaidBills = $tenant
    ? \App\Models\RentBill::where('tenant_id', $tenant->id)
    ->whereIn('status', ['unpaid', 'pending'])
    ->get()
    : collect();

    // ยอดรวมบิลที่ยังไม่ชำระเสร็จ
    $totalUnpaid = $unpaidBills->sum('total_amount');
    @endphp

    <div class="admin-layout">

        <!-- Sidebar -->
        <aside class="sidebar">

            <div class="logo">
                SOS อพาร์ตเมนต์
            </div>

            <div class="logo-subtitle">
                ระบบผู้เช่า
            </div>

            <nav class="sidebar-menu">

                <a href="{{ route('tenant.dashboard') }}"
                    class="{{ request()->is('tenant') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('tenant.bills.index') }}"
                    class="{{ request()->is('tenant/bills*') ? 'active' : '' }}">
                    ค่าเช่าของฉัน
                </a>

                <a href="{{ route('tenant.payments.history') }}"
                    class="{{ request()->is('tenant/payments*') ? 'active' : '' }}">
                    ประวัติการชำระเงิน
                </a>

                <a href="{{ route('tenant.repairs.index') }}"
                    class="{{ request()->is('tenant/repairs*') ? 'active' : '' }}">
                    แจ้งซ่อม
                </a>

                <a href="{{ route('tenant.notifications.index') }}"
                    class="{{ request()->is('tenant/notifications*') ? 'active' : '' }}">
                    การแจ้งเตือน

                    @if ($unreadCount > 0)
                    <span style="
                            background: #dc2626;
                            color: white;
                            padding: 3px 8px;
                            border-radius: 12px;
                            font-size: 12px;
                            margin-left: 8px;
                        ">
                        {{ $unreadCount }}
                    </span>
                    @endif
                </a>

                <form method="POST"
                    action="{{ route('logout') }}"
                    style="margin-top: 20px;">

                    @csrf

                    <button type="submit"
                        onclick="return confirm('ต้องการออกจากระบบใช่หรือไม่?')"
                        style="
                            width: 100%;
                            background: #dc2626;
                            color: white;
                            border: none;
                            padding: 12px;
                            border-radius: 8px;
                            cursor: pointer;
                            text-align: left;
                            font-size: 14px;
                        ">
                        ออกจากระบบ
                    </button>

                </form>

            </nav>

        </aside>

        <!-- Main Content -->
        <main class="main-content">

            <h1>Dashboard ผู้เช่า</h1>

            <p>
                ยินดีต้อนรับ
                {{ auth()->user()->fullname }}
            </p>

            <!-- Summary Cards -->
            <div class="dashboard-cards">

                <!-- ห้องพัก -->
                <div class="card">

                    <h3>ห้องพักของฉัน</h3>

                    <h2>
                        {{ $tenant?->room?->room_number ?? 'ไม่มีห้องพัก' }}
                    </h2>

                </div>

                <!-- ค่าเช่ารายเดือน -->
                <div class="card">

                    <h3>ค่าเช่ารายเดือน</h3>

                    <h2>
                        {{ number_format($tenant?->room?->monthly_rent ?? 0, 2) }}
                        บาท
                    </h2>

                </div>

                <!-- ยอดที่ยังไม่ชำระเสร็จ -->
                <div class="card">

                    <h3>ยอดที่ยังไม่ชำระเสร็จ</h3>

                    <h2>
                        {{ number_format($totalUnpaid, 2) }}
                        บาท
                    </h2>

                </div>

                <!-- จำนวนบิล -->
                <div class="card">

                    <h3>จำนวนบิลที่ยังไม่ชำระเสร็จ</h3>

                    <h2>
                        {{ $unpaidBills->count() }} บิล
                    </h2>

                </div>

            </div>

            <!-- Quick Menu -->
            <div class="dashboard-section">

                <h2>เมนูการใช้งาน</h2>

                <p>
                    ตรวจสอบบิลค่าเช่าและสถานะการชำระเงินของคุณ
                </p>

                <a href="{{ route('tenant.bills.index') }}"
                    style="
                       display: inline-block;
                       background: #2563eb;
                       color: white;
                       padding: 12px 20px;
                       border-radius: 8px;
                       text-decoration: none;
                       margin-top: 15px;
                   ">
                    ดูบิลค่าเช่าของฉัน
                </a>

            </div>

        </main>

    </div>

</body>

</html>