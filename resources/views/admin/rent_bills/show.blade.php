<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดบิลค่าเช่า - SOS อพาร์ตเมนต์</title>
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

            <h1>รายละเอียดบิลค่าเช่า</h1>
            <p>ข้อมูลบิลค่าเช่าหมายเลข #{{ $bill->id }}</p>

            <div class="dashboard-section">

                <h2>ข้อมูลผู้เช่า</h2>

                <p>
                    <strong>ชื่อผู้เช่า:</strong>
                    {{ $bill->tenant?->user?->fullname ?? '-' }}
                </p>

                <p>
                    <strong>หมายเลขห้อง:</strong>
                    {{ $bill->tenant?->room?->room_number ?? '-' }}
                </p>

                <p>
                    <strong>เดือนที่เรียกเก็บ:</strong>
                    {{ $bill->billing_month }}
                </p>

                <p>
                    <strong>วันครบกำหนดชำระ:</strong>
                    {{ $bill->due_date ?? '-' }}
                </p>

                <hr style="margin: 25px 0;">

                <h2>รายการค่าใช้จ่าย</h2>

                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>รายการ</th>
                            <th>จำนวนเงิน (บาท)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>ค่าเช่าห้อง</td>
                            <td>{{ number_format($bill->room_rent, 2) }}</td>
                        </tr>

                        <tr>
                            <td>ค่าน้ำ</td>
                            <td>{{ number_format($bill->water_cost, 2) }}</td>
                        </tr>

                        <tr>
                            <td>ค่าไฟ</td>
                            <td>{{ number_format($bill->electric_cost, 2) }}</td>
                        </tr>

                        <tr>
                            <td>ค่าใช้จ่ายอื่น ๆ</td>
                            <td>{{ number_format($bill->other_cost, 2) }}</td>
                        </tr>

                        <tr>
                            <td><strong>ยอดรวมทั้งหมด</strong></td>
                            <td>
                                <strong>
                                    {{ number_format($bill->total_amount, 2) }}
                                </strong>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div style="margin-top: 25px;">
                    <h2>สถานะการชำระเงิน</h2>

                    @if ($bill->status === 'unpaid')
                    <span style="color: #ef4444;">
                        ยังไม่ชำระ
                    </span>
                    @elseif ($bill->status === 'pending')
                    <span style="color: #f59e0b;">
                        รอตรวจสอบ
                    </span>
                    @elseif ($bill->status === 'paid')
                    <span style="color: #22c55e;">
                        ชำระแล้ว
                    </span>
                    @else
                    {{ $bill->status }}
                    @endif
                </div>

                <div style="margin-top: 30px;">
                    <a href="{{ route('admin.rent_bills.index') }}"
                        style="display: inline-block;
                          background: #475569;
                          color: white;
                          padding: 10px 18px;
                          border-radius: 8px;
                          text-decoration: none;">
                        กลับหน้ารายการบิล
                    </a>
                </div>

            </div>

        </main>
    </div>

</body>

</html>