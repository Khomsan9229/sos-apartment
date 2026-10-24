<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>บิลค่าเช่าของฉัน - SOS อพาร์ตเมนต์</title>

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

            <h1>บิลค่าเช่าของฉัน</h1>

            <p>รายการบิลค่าเช่าและสถานะการชำระเงิน</p>

            <div class="dashboard-section">

                <table class="rooms-table">

                    <thead>
                        <tr>
                            <th>เลขที่บิล</th>
                            <th>เดือนที่เรียกเก็บ</th>
                            <th>ค่าเช่าห้อง</th>
                            <th>ค่าน้ำ</th>
                            <th>ค่าไฟ</th>
                            <th>ค่าใช้จ่ายอื่น</th>
                            <th>ยอดรวม</th>
                            <th>วันครบกำหนด</th>
                            <th>สถานะ</th>
                            <th>จัดการ</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse ($bills as $bill)

                        <tr>

                            <td>
                                #{{ $bill->id }}
                            </td>

                            <td>
                                {{ $bill->billing_month }}
                            </td>

                            <td>
                                {{ number_format($bill->room_rent, 2) }}
                            </td>

                            <td>
                                {{ number_format($bill->water_cost, 2) }}
                            </td>

                            <td>
                                {{ number_format($bill->electric_cost, 2) }}
                            </td>

                            <td>
                                {{ number_format($bill->other_cost, 2) }}
                            </td>

                            <td>
                                <strong>
                                    {{ number_format($bill->total_amount, 2) }}
                                    บาท
                                </strong>
                            </td>

                            <td>
                                {{ $bill->due_date ?? '-' }}
                            </td>

                            <!-- สถานะบิล -->
                            <td>

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

                                @endif

                            </td>

                            <!-- จัดการ -->
                            <td>

                                @if ($bill->status === 'unpaid')


                                <a href="{{ route('tenant.payments.create', $bill->id) }}"
                                    style="
       display: inline-block;
       background: #2563eb;
       color: white;
       padding: 8px 12px;
       border-radius: 6px;
       text-decoration: none;
       white-space: nowrap;
   ">
                                    ชำระค่าเช่า
                                </a>


                                @elseif ($bill->status === 'pending')

                                <span style="color: #f59e0b;">
                                    รอตรวจสอบ
                                </span>

                                @elseif ($bill->status === 'paid')

                                <span style="color: #22c55e;">
                                    ชำระแล้ว
                                </span>

                                @endif

                            </td>

                        </tr>

                        @empty

                        <tr>
                            <td colspan="10"
                                style="text-align: center; padding: 20px;">
                                ยังไม่มีบิลค่าเช่า
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