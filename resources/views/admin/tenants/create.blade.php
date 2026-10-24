<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มผู้เช่า - SOS อพาร์ตเมนต์</title>
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

            <h1>เพิ่มผู้เช่าใหม่</h1>
            <p>กรอกข้อมูลผู้เช่าเพื่อเพิ่มเข้าสู่ระบบ</p>

            <div class="dashboard-section">

                @if ($errors->any())
                <div style="color: #dc2626; margin-bottom: 20px;">
                    <strong>กรุณาตรวจสอบข้อมูล</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.tenants.store') }}" method="POST">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text" name="fullname"
                            value="{{ old('fullname') }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ชื่อผู้ใช้ (Username)</label>
                        <input type="text" name="username"
                            value="{{ old('username') }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>รหัสผ่าน</label>
                        <input type="password" name="password"
                            minlength="8" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>เบอร์โทรศัพท์</label>
                        <input type="text" name="phone"
                            value="{{ old('phone') }}">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>เลขบัตรประชาชน</label>
                        <input type="text" name="id_card"
                            value="{{ old('id_card') }}"
                            maxlength="13">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ห้องพัก</label>
                        <select name="room_id" required>
                            <option value="">-- เลือกห้องพัก --</option>

                            @foreach ($rooms as $room)
                            <option value="{{ $room->id }}"
                                @selected(old('room_id')==$room->id)>
                                {{ $room->room_number }}
                                ({{ number_format($room->monthly_rent, 2) }} บาท)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>วันที่เข้าพัก</label>
                        <input type="date" name="move_in_date"
                            value="{{ old('move_in_date') }}"
                            required>
                    </div>

                    <button type="submit"
                        style="background: #2563eb;
                               color: white;
                               border: none;
                               padding: 10px 18px;
                               border-radius: 8px;
                               cursor: pointer;">
                        บันทึกผู้เช่า
                    </button>

                    <a href="{{ route('admin.tenants.index') }}"
                        style="margin-left: 15px;">
                        ยกเลิก
                    </a>

                </form>

            </div>

        </main>
    </div>

</body>

</html>