<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการบิลค่าเช่า - SOS อพาร์ตเมนต์</title>
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

            <h1>จัดการบิลค่าเช่า</h1>
            <p>รายการบิลค่าเช่าทั้งหมดในระบบ</p>

            <a href="{{ route('admin.rent_bills.create') }}"
                style="display: inline-block;
          background: #2563eb;
          color: white;
          padding: 10px 18px;
          border-radius: 8px;
          text-decoration: none;
          margin-bottom: 20px;">
                + สร้างบิลค่าเช่า
            </a>


            <div class="dashboard-section">

                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>เลขที่บิล</th>
                            <th>ชื่อผู้เช่า</th>
                            <th>ห้องพัก</th>
                            <th>เดือนที่เรียกเก็บ</th>
                            <th>ยอดรวม</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($bills as $bill)
                        <tr>
                            <td>{{ $bill->id }}</td>

                            <td>
                                {{ $bill->tenant?->user?->fullname ?? '-' }}
                            </td>

                            <td>
                                {{ $bill->tenant?->room?->room_number ?? '-' }}
                            </td>

                            <td>{{ $bill->billing_month }}</td>

                            <td>
                                {{ number_format($bill->total_amount, 2) }} บาท
                            </td>

                            <td>
                                @if ($bill->status === 'unpaid')
                                <span style="color: #dc2626;">
                                    ยังไม่ชำระ
                                </span>
                                @elseif ($bill->status === 'pending')
                                <span style="color: #d97706;">
                                    รอตรวจสอบ
                                </span>
                                @elseif ($bill->status === 'paid')
                                <span style="color: #16a34a;">
                                    ชำระแล้ว
                                </span>
                                @else
                                {{ $bill->status }}
                                @endif
                            </td>

                            <td>
                                <a href="{{ route('admin.rent_bills.show', $bill->id) }}"
                                    style="display: inline-block;
              background: #2563eb;
              color: white;
              padding: 8px 14px;
              border-radius: 6px;
              text-decoration: none;">
                                    ดูรายละเอียด
                                </a>
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="text-align: center;">
                                ยังไม่มีข้อมูลบิลค่าเช่า
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