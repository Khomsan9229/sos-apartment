<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>จัดการแจ้งซ่อม - SOS อพาร์ตเมนต์</title>

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

            <h1>จัดการแจ้งซ่อม</h1>

            <p>รายการแจ้งซ่อมทั้งหมดจากผู้เช่า</p>

            @if (session('success'))
            <div style="
                background: #dcfce7;
                color: #166534;
                padding: 12px 16px;
                border-radius: 8px;
                margin: 15px 0;
            ">
                {{ session('success') }}
            </div>
            @endif

            <div class="dashboard-section">

                <div style="overflow-x: auto;">

                    <table class="rooms-table">

                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>ชื่อผู้เช่า</th>
                                <th>ห้องพัก</th>
                                <th>หัวข้อแจ้งซ่อม</th>
                                <th>รายละเอียด</th>
                                <th>วันที่แจ้ง</th>
                                <th>สถานะ</th>
                                <th>หมายเหตุ Admin</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($repairs as $repair)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $repair->tenant?->user?->fullname ?? '-' }}
                                </td>

                                <td>
                                    {{ $repair->tenant?->room?->room_number ?? '-' }}
                                </td>

                                <td>
                                    {{ $repair->title }}
                                </td>

                                <td>
                                    {{ $repair->description }}
                                </td>

                                <td>
                                    {{ $repair->created_at
                                        ? $repair->created_at->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <!-- สถานะ -->
                                <td>

                                    @if ($repair->status === 'pending')

                                    <span style="color: #f59e0b;">
                                        รอดำเนินการ
                                    </span>

                                    @elseif ($repair->status === 'in_progress')

                                    <span style="color: #2563eb;">
                                        กำลังดำเนินการ
                                    </span>

                                    @elseif ($repair->status === 'completed')

                                    <span style="color: #16a34a;">
                                        ซ่อมเสร็จแล้ว
                                    </span>

                                    @else

                                    {{ $repair->status }}

                                    @endif

                                </td>

                                <td>
                                    {{ $repair->admin_note ?: '-' }}
                                </td>

                                <!-- ปุ่มจัดการ -->
                                <td>


                                    <a href="{{ route('admin.repairs.edit', $repair->id) }}"
                                        style="
       display: inline-block;
       background: #2563eb;
       color: white;
       padding: 8px 12px;
       border-radius: 6px;
       text-decoration: none;
       white-space: nowrap;
   ">
                                        จัดการ
                                    </a>


                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="9"
                                    style="
                                        text-align: center;
                                        padding: 20px;
                                    ">
                                    ยังไม่มีรายการแจ้งซ่อม
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>