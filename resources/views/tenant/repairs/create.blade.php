<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>แจ้งซ่อมใหม่ - SOS อพาร์ตเมนต์</title>

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

            <h1>แจ้งซ่อมใหม่</h1>

            @if ($errors->any())
            <div style="
        background: #fee2e2;
        color: #991b1b;
        padding: 12px 16px;
        border-radius: 8px;
        margin: 15px 0;
    ">
                @foreach ($errors->all() as $error)
                <p>{{ $error }}</p>
                @endforeach
            </div>
            @endif

            <p>กรอกข้อมูลปัญหาที่ต้องการแจ้งให้ผู้ดูแลทราบ</p>

            <div class="dashboard-section" style="max-width: 650px;">

                <h2>แบบฟอร์มแจ้งซ่อม</h2>

                <form action="{{ route('tenant.repairs.store') }}" method="POST">

                    @csrf

                    <div style="margin-bottom: 20px;">

                        <label for="title">
                            <strong>หัวข้อแจ้งซ่อม</strong>
                        </label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            value="{{ old('title') }}"
                            placeholder="เช่น เครื่องปรับอากาศไม่เย็น"
                            maxlength="255"
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

                    <div style="margin-bottom: 20px;">

                        <label for="description">
                            <strong>รายละเอียดปัญหา</strong>
                        </label>

                        <textarea
                            id="description"
                            name="description"
                            rows="6"
                            placeholder="อธิบายปัญหาที่พบและบริเวณที่ต้องการให้ซ่อม"
                            required
                            style="
                            display: block;
                            width: 100%;
                            box-sizing: border-box;
                            padding: 12px;
                            margin-top: 8px;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                            resize: vertical;
                        ">{{ old('description') }}</textarea>

                    </div>


                    <button
                        type="submit"
                        onclick="return confirm('ยืนยันการส่งคำร้องแจ้งซ่อม?')"
                        style="
        background: #2563eb;
        color: white;
        border: none;
        padding: 12px 20px;
        border-radius: 8px;
        cursor: pointer;
    ">
                        ส่งคำร้องแจ้งซ่อม
                    </button>


                    <a href="{{ route('tenant.repairs.index') }}"
                        style="
                       display: inline-block;
                       margin-left: 15px;
                       color: #2563eb;
                       text-decoration: none;
                   ">
                        ย้อนกลับ
                    </a>

                </form>

            </div>

        </main>

    </div>

</body>

</html>