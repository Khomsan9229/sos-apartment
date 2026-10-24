<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>รายการแจ้งซ่อม - SOS อพาร์ตเมนต์</title>

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

                @php
                $unreadCount = \App\Models\Notification::where(
                'user_id',
                auth()->id()
                )
                ->where('is_read', 0)
                ->count();
                @endphp

                <a href="{{ route('tenant.notifications.index') }}"
                    class="{{ request()->is('tenant/notifications*') ? 'active' : '' }}">
                    การแจ้งเตือน

                    @if ($unreadCount > 0)
                    ({{ $unreadCount }})
                    @endif
                </a>

                <form method="POST" action="{{ route('logout') }}">
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

            <h1>รายการแจ้งซ่อม</h1>

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


            <p>ตรวจสอบรายการแจ้งซ่อมและติดตามสถานะการดำเนินการ</p>

            <div style="margin: 20px 0;">


                <a href="{{ route('tenant.repairs.create') }}"
                    style="
       display: inline-block;
       background: #2563eb;
       color: white;
       padding: 12px 20px;
       border-radius: 8px;
       text-decoration: none;
   ">
                    + แจ้งซ่อมใหม่
                </a>


            </div>

            <div class="dashboard-section">

                <div style="overflow-x: auto;">

                    <table class="rooms-table">

                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>หัวข้อแจ้งซ่อม</th>
                                <th>รายละเอียด</th>
                                <th>วันที่แจ้ง</th>
                                <th>สถานะ</th>
                                <th>หมายเหตุจาก Admin</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($repairs as $repair)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    {{ $repair->title }}
                                </td>

                                <td>
                                    {{ $repair->description }}
                                </td>

                                <td>
                                    {{ $repair->created_at
                                        ? \Carbon\Carbon::parse($repair->created_at)->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

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

                            </tr>

                            @empty

                            <tr>
                                <td colspan="6"
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