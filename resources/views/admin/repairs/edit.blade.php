<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>แก้ไขแจ้งซ่อม - SOS อพาร์ตเมนต์</title>

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


        <!-- Main Content -->
        <main class="main-content">

            <h1>แก้ไขสถานะการแจ้งซ่อม</h1>

            <p>ตรวจสอบข้อมูลและอัปเดตสถานะการดำเนินการ</p>

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

            <div class="dashboard-section" style="max-width: 700px;">

                <h2>รายละเอียดการแจ้งซ่อม</h2>

                <p>
                    <strong>เลขที่แจ้งซ่อม:</strong>
                    #{{ $repair->id }}
                </p>

                <p>
                    <strong>ชื่อผู้เช่า:</strong>
                    {{ $repair->tenant?->user?->fullname ?? '-' }}
                </p>

                <p>
                    <strong>หมายเลขห้อง:</strong>
                    {{ $repair->tenant?->room?->room_number ?? '-' }}
                </p>

                <p>
                    <strong>หัวข้อแจ้งซ่อม:</strong>
                    {{ $repair->title }}
                </p>

                <p>
                    <strong>รายละเอียดปัญหา:</strong>
                    {{ $repair->description }}
                </p>

                <p>
                    <strong>วันที่แจ้ง:</strong>
                    {{ $repair->created_at
                    ? $repair->created_at->format('d/m/Y H:i')
                    : '-' }}
                </p>

                <hr style="margin: 25px 0;">

                <h2>จัดการสถานะการซ่อม</h2>

                <form
                    action="{{ route('admin.repairs.update', $repair->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <!-- เลือกสถานะ -->
                    <div style="margin-bottom: 20px;">

                        <label for="status">
                            <strong>สถานะการแจ้งซ่อม</strong>
                        </label>

                        <select
                            id="status"
                            name="status"
                            required
                            style="
                            display: block;
                            width: 100%;
                            padding: 12px;
                            margin-top: 8px;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                        ">

                            <option value="pending"
                                {{ old('status', $repair->status) == 'pending' ? 'selected' : '' }}>
                                รอดำเนินการ
                            </option>

                            <option value="in_progress"
                                {{ old('status', $repair->status) == 'in_progress' ? 'selected' : '' }}>
                                กำลังดำเนินการ
                            </option>

                            <option value="completed"
                                {{ old('status', $repair->status) == 'completed' ? 'selected' : '' }}>
                                ซ่อมเสร็จแล้ว
                            </option>

                        </select>

                    </div>

                    <!-- หมายเหตุ Admin -->
                    <div style="margin-bottom: 20px;">

                        <label for="admin_note">
                            <strong>หมายเหตุจากผู้ดูแล</strong>
                        </label>

                        <textarea
                            id="admin_note"
                            name="admin_note"
                            rows="5"
                            maxlength="2000"
                            placeholder="เช่น ช่างจะเข้าตรวจสอบในวันพรุ่งนี้"
                            style="
                            display: block;
                            width: 100%;
                            box-sizing: border-box;
                            padding: 12px;
                            margin-top: 8px;
                            border: 1px solid #d1d5db;
                            border-radius: 8px;
                            resize: vertical;
                        ">{{ old('admin_note', $repair->admin_note) }}</textarea>

                    </div>

                    <button
                        type="submit"
                        onclick="return confirm('ยืนยันการบันทึกข้อมูลแจ้งซ่อม?')"
                        style="
                        background: #2563eb;
                        color: white;
                        border: none;
                        padding: 12px 20px;
                        border-radius: 8px;
                        cursor: pointer;
                    ">
                        บันทึกการเปลี่ยนแปลง
                    </button>

                    <a href="{{ route('admin.repairs.index') }}"
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