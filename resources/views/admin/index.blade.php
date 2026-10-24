<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>SOS อพาร์ตเมนต์ - Admin Dashboard</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="admin-layout">

        <!-- Sidebar -->

        <aside class="sidebar">

            <div class="logo">
                SOS อพาร์ตเมนต์
            </div>

            <div class="logo-subtitle">
                ระบบจัดการหอพัก
            </div>

            <nav class="sidebar-menu">

                <a href="/admin"
                    class="{{ request()->is('admin') ? 'active' : '' }}">
                    Dashboard
                </a>

                <a href="{{ route('admin.rooms.index') }}"
                    class="{{ request()->is('admin/rooms*') ? 'active' : '' }}">
                    จัดการห้องพัก
                </a>

                <a href="{{ route('admin.tenants.index') }}"
                    class="{{ request()->is('admin/tenants*') ? 'active' : '' }}">
                    ผู้เช่า
                </a>

                <a href="{{ route('admin.rent_bills.index') }}"
                    class="{{ request()->is('admin/rent-bills*') ? 'active' : '' }}">
                    ค่าเช่า
                </a>

                <a href="{{ route('admin.payments.index') }}"
                    class="{{ request()->is('admin/payments*') ? 'active' : '' }}">
                    การชำระเงิน
                </a>

                <a href="{{ route('admin.repairs.index') }}"
                    class="{{ request()->is('admin/repairs*') ? 'active' : '' }}">
                    จัดการแจ้งซ่อม
                </a>

                <a href="{{ route('admin.expenses.index') }}"
                    class="{{ request()->is('admin/expenses*') ? 'active' : '' }}">
                    จัดการค่าใช้จ่าย
                </a>

                <a href="{{ route('admin.reports.index') }}"
                    class="{{ request()->is('admin/reports*') ? 'active' : '' }}">
                    รายงาน
                </a>

                <form method="POST" action="/logout">
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
                ">
                        ออกจากระบบ
                    </button>
                </form>

            </nav>

        </aside>



        <!-- Main Content -->
        <main class="main-content">

            <!-- Header -->
            <div class="dashboard-header">

                <h1>
                    Admin Dashboard
                </h1>

                <p>
                    ภาพรวมระบบจัดการ SOS อพาร์ตเมนต์
                </p>

            </div>


            <!-- Statistics -->
            <div class="stats-grid">

                <div class="stat-card">
                    <div class="stat-title">
                        ห้องทั้งหมด
                    </div>

                    <div class="stat-number">
                        {{ $totalRooms }}
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-title">
                        ห้องว่าง
                    </div>

                    <div class="stat-number">
                        {{ $availableRooms }}
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-title">
                        ห้องมีผู้เช่า
                    </div>

                    <div class="stat-number">
                        {{ $occupiedRooms }}
                    </div>
                </div>


                <div class="stat-card">
                    <div class="stat-title">
                        ผู้เช่าปัจจุบัน
                    </div>

                    <div class="stat-number">
                        {{ $totalTenants }}
                    </div>
                </div>

            </div>


            <!-- Dashboard Sections -->
            <div class="dashboard-grid">

                <!-- Rooms -->
                <section class="dashboard-section">

                    <h2>
                        สถานะห้องพัก
                    </h2>

                    <div class="info-row">
                        <span class="info-label">
                            ห้องว่าง
                        </span>

                        <span class="info-value">
                            {{ $availableRooms }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            ห้องมีผู้เช่า
                        </span>

                        <span class="info-value">
                            {{ $occupiedRooms }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            ห้องกำลังซ่อม
                        </span>

                        <span class="info-value">
                            {{ $repairRooms }}
                        </span>
                    </div>

                </section>


                <!-- Rent Bills -->
                <section class="dashboard-section">

                    <h2>
                        สถานะค่าเช่า
                    </h2>

                    <div class="info-row">
                        <span class="info-label">
                            ยังไม่จ่าย
                        </span>

                        <span class="info-value">
                            {{ $unpaidBills }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            รอตรวจสอบ
                        </span>

                        <span class="info-value">
                            {{ $pendingBills }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            จ่ายแล้ว
                        </span>

                        <span class="info-value">
                            {{ $paidBills }}
                        </span>
                    </div>

                </section>


                <!-- Payments -->
                <section class="dashboard-section">

                    <h2>
                        การชำระเงิน
                    </h2>

                    <div class="info-row">
                        <span class="info-label">
                            รอตรวจสอบ
                        </span>

                        <span class="info-value">
                            {{ $pendingPayments }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            อนุมัติแล้ว
                        </span>

                        <span class="info-value">
                            {{ $approvedPayments }}
                        </span>
                    </div>

                </section>


                <!-- Repairs -->
                <section class="dashboard-section">

                    <h2>
                        การแจ้งซ่อม
                    </h2>

                    <div class="info-row">
                        <span class="info-label">
                            รอดำเนินการ
                        </span>

                        <span class="info-value">
                            {{ $pendingRepairs }}
                        </span>
                    </div>

                    <div class="info-row">
                        <span class="info-label">
                            กำลังดำเนินการ
                        </span>

                        <span class="info-value">
                            {{ $inProgressRepairs }}
                        </span>
                    </div>

                </section>

            </div>

        </main>

    </div>

</body>

</html>