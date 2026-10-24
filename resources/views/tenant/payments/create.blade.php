<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ชำระค่าเช่า - SOS อพาร์ตเมนต์</title>

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

            <h1>ชำระค่าเช่า</h1>

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

            <p>ส่งหลักฐานการชำระเงินเพื่อให้ผู้ดูแลตรวจสอบ</p>

            <div class="dashboard-section" style="max-width: 650px;">

                <h2>รายละเอียดบิลค่าเช่า</h2>

                <p>
                    <strong>เลขที่บิล:</strong>
                    #{{ $bill->id }}
                </p>

                <p>
                    <strong>เดือนที่เรียกเก็บ:</strong>
                    {{ $bill->billing_month }}
                </p>

                <p>
                    <strong>วันครบกำหนด:</strong>
                    {{ $bill->due_date ?? '-' }}
                </p>

                <p>
                    <strong>ยอดที่ต้องชำระ:</strong>
                    <span style="color: #2563eb; font-size: 24px; font-weight: bold;">
                        {{ number_format($bill->total_amount, 2) }} บาท
                    </span>
                </p>

                <hr style="margin: 20px 0;">

                <h2>อัปโหลดหลักฐานการชำระเงิน</h2>

                <form action="{{ route('tenant.payments.store', $bill->id) }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf

                    <div style="margin-bottom: 18px;">
                        <label for="amount">
                            จำนวนเงินที่ชำระ
                        </label>

                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            value="{{ old('amount', $bill->total_amount) }}"
                            min="0.01"
                            step="0.01"
                            required
                            style="
                            display: block;
                            width: 100%;
                            box-sizing: border-box;
                            padding: 12px;
                            margin-top: 8px;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                        ">
                    </div>

                    <div style="margin-bottom: 18px;">
                        <label for="slip_image">
                            รูปหลักฐานการโอนเงิน
                        </label>

                        <input
                            type="file"
                            id="slip_image"
                            name="slip_image"
                            accept=".jpg,.jpeg,.png,image/jpeg,image/png"
                            required
                            style="
                            display: block;
                            width: 100%;
                            margin-top: 8px;
                        ">

                        <small>
                            รองรับไฟล์ JPG และ PNG
                        </small>
                    </div>


                    <button
                        type="submit"
                        onclick="return confirm('ยืนยันการส่งหลักฐานการชำระเงิน?')"
                        style="
        background: #2563eb;
        color: white;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        cursor: pointer;
    ">
                        ยืนยันการส่งหลักฐาน
                    </button>


                    <a href="{{ route('tenant.bills.index') }}"
                        style="
                       display: inline-block;
                       margin-left: 10px;
                       color: #374151;
                   ">
                        ย้อนกลับ
                    </a>

                </form>

            </div>

        </main>

    </div>

</body>

</html>