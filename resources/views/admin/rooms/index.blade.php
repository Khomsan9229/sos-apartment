<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการห้องพัก - SOS อพาร์ตเมนต์</title>
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
            <h1>จัดการห้องพัก</h1>

            @if (session('success'))
            <div style="background: #dcfce7;
                color: #166534;
                padding: 12px 16px;
                border-radius: 8px;
                margin: 15px 0;">
                {{ session('success') }}
            </div>
            @endif

            @if (session('error'))
            <div style="background: #fee2e2;
                color: #991b1b;
                padding: 12px 16px;
                border-radius: 8px;
                margin: 15px 0;">
                {{ session('error') }}
            </div>
            @endif
            <p>รายการห้องพักทั้งหมดในระบบ</p>

            <a href="{{ route('admin.rooms.create') }}"
                style="display: inline-block;
          background: #2563eb;
          color: white;
          padding: 10px 18px;
          border-radius: 8px;
          text-decoration: none;
          margin-bottom: 20px;">
                + เพิ่มห้องพัก
            </a>

            <div class="dashboard-section">
                <table class="rooms-table" width="100%" cellpadding="12">
                    <thead>
                        <tr>
                            <th>หมายเลขห้อง</th>
                            <th>ชั้น</th>
                            <th>ประเภทห้อง</th>
                            <th>ค่าเช่า/เดือน</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($rooms as $room)
                        <tr>
                            <td>{{ $room->room_number }}</td>
                            <td>{{ $room->floor }}</td>
                            <td>{{ $room->room_type ?? '-' }}</td>
                            <td>{{ number_format($room->monthly_rent, 2) }} บาท</td>
                            <td>
                                @if ($room->status === 'available')
                                ว่าง
                                @elseif ($room->status === 'occupied')
                                มีผู้เช่า
                                @elseif ($room->status === 'repair')
                                ปิดซ่อม
                                @else
                                {{ $room->status }}
                                @endif
                            </td>


                            <td>
                                <!-- ปุ่มแก้ไข -->
                                <a href="{{ route('admin.rooms.edit', $room->id) }}"
                                    style="display: inline-block;
              background: #f59e0b;
              color: white;
              padding: 7px 14px;
              border-radius: 6px;
              text-decoration: none;">
                                    แก้ไข
                                </a>

                                <!-- ปุ่มลบ -->
                                <form action="{{ route('admin.rooms.destroy', $room->id) }}"
                                    method="POST"
                                    style="display: inline-block; margin-left: 5px;"
                                    onsubmit="return confirm('คุณต้องการลบห้อง {{ $room->room_number }} ใช่หรือไม่?');">

                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                        style="background: #dc2626;
                       color: white;
                       padding: 7px 14px;
                       border: none;
                       border-radius: 6px;
                       cursor: pointer;">
                                        ลบ
                                    </button>
                                </form>
                            </td>


                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" style="text-align:center;">
                                ยังไม่มีข้อมูลห้องพัก
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