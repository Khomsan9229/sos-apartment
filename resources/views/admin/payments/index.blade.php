<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการการชำระเงิน - SOS อพาร์ตเมนต์</title>
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

            <h1>จัดการการชำระเงิน</h1>

            @if (session('success'))
            <div style="
        background: #dcfce7;
        color: #166534;
        padding: 12px;
        border-radius: 8px;
        margin: 15px 0;
    ">
                {{ session('success') }}
            </div>
            @endif

            @if ($errors->any())
            <div style="
        background: #fee2e2;
        color: #991b1b;
        padding: 12px;
        border-radius: 8px;
        margin: 15px 0;
    ">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <p>รายการชำระเงินทั้งหมดในระบบ</p>


            <div class="dashboard-section">

                <table class="rooms-table">
                    <thead>
                        <tr>
                            <th>เลขที่รายการ</th>
                            <th>ชื่อผู้เช่า</th>
                            <th>เลขที่บิล</th>
                            <th>จำนวนเงิน</th>
                            <th>วันที่ชำระ</th>
                            <th>สถานะ</th>
                            <th>หลักฐาน</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->id }}</td>

                            <td>
                                {{ $payment->rentBill?->tenant?->user?->fullname ?? '-' }}
                            </td>

                            <td>
                                #{{ $payment->bill_id }}
                            </td>

                            <td>
                                {{ number_format($payment->amount, 2) }} บาท
                            </td>

                            <td>
                                {{ $payment->payment_date ?? '-' }}
                            </td>

                            <td>
                                @if ($payment->status === 'pending')
                                <span style="color: #f59e0b;">
                                    รอตรวจสอบ
                                </span>
                                @elseif ($payment->status === 'approved')
                                <span style="color: #22c55e;">
                                    อนุมัติแล้ว
                                </span>
                                @elseif ($payment->status === 'rejected')
                                <span style="color: #ef4444;">
                                    ไม่อนุมัติ
                                </span>
                                @else
                                {{ $payment->status }}
                                @endif
                            </td>

                            <td>
                                @if ($payment->slip_image)
                                <a href="{{ asset('storage/' . $payment->slip_image) }}"
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    style="color: #60a5fa;">
                                    ดูหลักฐาน
                                </a>
                                @else
                                -
                                @endif
                            </td>

                            <td>
                                @if ($payment->status === 'pending')

                                <div style="display: flex; gap: 8px;">

                                    <!-- ปุ่มอนุมัติ -->
                                    <form
                                        action="{{ route('admin.payments.approve', $payment->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('ยืนยันการอนุมัติการชำระเงิน?')">
                                        @csrf

                                        <button type="submit"
                                            style="background: #22c55e;
                           color: white;
                           border: none;
                           padding: 8px 12px;
                           border-radius: 6px;
                           cursor: pointer;">
                                            อนุมัติ
                                        </button>
                                    </form>

                                    <!-- ปุ่มไม่อนุมัติ -->
                                    <form
                                        action="{{ route('admin.payments.reject', $payment->id) }}"
                                        method="POST"
                                        onsubmit="return confirm('ยืนยันการไม่อนุมัติการชำระเงิน?')">
                                        @csrf

                                        <button type="submit"
                                            style="background: #ef4444;
                           color: white;
                           border: none;
                           padding: 8px 12px;
                           border-radius: 6px;
                           cursor: pointer;">
                                            ไม่อนุมัติ
                                        </button>
                                    </form>

                                </div>

                                @elseif ($payment->status === 'approved')

                                <span style="color: #22c55e;">
                                    อนุมัติแล้ว
                                </span>

                                @elseif ($payment->status === 'rejected')

                                <span style="color: #ef4444;">
                                    ไม่อนุมัติแล้ว
                                </span>

                                @endif
                            </td>

                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" style="text-align: center;">
                                ยังไม่มีข้อมูลการชำระเงิน
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