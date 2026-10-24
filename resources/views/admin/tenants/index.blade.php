<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการผู้เช่า - SOS อพาร์ตเมนต์</title>
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
</head>

<body>

    <div class="admin-layout">


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


        <main class="main-content">

            <h1>จัดการผู้เช่า</h1>
            <p>รายชื่อผู้เช่าทั้งหมดในระบบ</p>


            <a href="{{ route('admin.tenants.create') }}"
                style="display: inline-block;
          background: #2563eb;
          color: white;
          padding: 10px 18px;
          border-radius: 8px;
          text-decoration: none;
          margin-bottom: 20px;">
                + เพิ่มผู้เช่า
            </a>


            <div class="dashboard-section">

                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>ชื่อผู้เช่า</th>
                            <th>หมายเลขห้อง</th>
                            <th>เบอร์โทรศัพท์</th>
                            <th>วันที่เข้าพัก</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tenants as $tenant)
                        <tr>
                            <td>{{ $tenant->user?->fullname ?? '-' }}</td>

                            <td>{{ $tenant->room?->room_number ?? '-' }}</td>

                            <td>{{ $tenant->phone ?? '-' }}</td>

                            <td>{{ $tenant->move_in_date ?? '-' }}</td>

                            <td>
                                @if ($tenant->status === 'active')
                                กำลังพักอาศัย
                                @elseif ($tenant->status === 'moved_out')
                                ย้ายออกแล้ว
                                @else
                                {{ $tenant->status }}
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.tenants.edit', $tenant->id) }}"
                                    style="display: inline-block;
              background: #f59e0b;
              color: white;
              padding: 8px 14px;
              border-radius: 6px;
              text-decoration: none;">
                                    แก้ไข
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                ยังไม่มีข้อมูลผู้เช่า
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

            </div>

        </main>
    </div>

</body>

</html>