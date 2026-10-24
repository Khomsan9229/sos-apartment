<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ประวัติการชำระเงิน - SOS อพาร์ตเมนต์</title>

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

            <h1>ประวัติการชำระเงิน</h1>

            <p>
                รายการหลักฐานการชำระค่าเช่าที่คุณเคยส่ง
            </p>

            <div class="dashboard-section">

                <div style="overflow-x: auto;">

                    <table class="rooms-table">

                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>เลขที่บิล</th>
                                <th>เดือนที่เรียกเก็บ</th>
                                <th>ยอดชำระ</th>
                                <th>วันที่ส่งชำระ</th>
                                <th>สถานะ</th>
                                <th>หลักฐาน</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($payments as $payment)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>
                                    #{{ $payment->bill_id }}
                                </td>

                                <td>
                                    {{ $payment->rentBill?->billing_month ?? '-' }}
                                </td>

                                <td>
                                    {{ number_format($payment->amount, 2) }}
                                    บาท
                                </td>

                                <td>
                                    {{ $payment->payment_date
                                        ? \Carbon\Carbon::parse($payment->payment_date)->format('d/m/Y H:i')
                                        : '-' }}
                                </td>

                                <td>

                                    @if ($payment->status === 'pending')

                                    <span style="color: #f59e0b;">
                                        รอตรวจสอบ
                                    </span>

                                    @elseif ($payment->status === 'approved')

                                    <span style="color: #16a34a;">
                                        อนุมัติแล้ว
                                    </span>

                                    @elseif ($payment->status === 'rejected')

                                    <span style="color: #dc2626;">
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
                                        style="
                                               color: #2563eb;
                                               text-decoration: none;
                                           ">
                                        ดูสลิป
                                    </a>

                                    @else

                                    <span style="color: #6b7280;">
                                        ไม่มีสลิป
                                    </span>

                                    @endif

                                </td>

                            </tr>

                            @empty

                            <tr>
                                <td colspan="7"
                                    style="
                                        text-align: center;
                                        padding: 20px;
                                    ">
                                    ยังไม่มีประวัติการชำระเงิน
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            <a href="{{ route('tenant.bills.index') }}"
                style="
               display: inline-block;
               margin-top: 20px;
               color: #2563eb;
               text-decoration: none;
           ">
                ← กลับไปหน้าบิลค่าเช่า
            </a>

        </main>

    </div>

</body>

</html>