<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มห้องพัก - SOS อพาร์ตเมนต์</title>
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

            <h1>เพิ่มห้องพักใหม่</h1>
            <p>กรอกข้อมูลห้องพักที่ต้องการเพิ่มเข้าสู่ระบบ</p>

            <div class="dashboard-section">

                @if ($errors->any())
                <div style="color: red; margin-bottom: 15px;">
                    <strong>กรุณาตรวจสอบข้อมูล:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="/admin/rooms" method="POST">
                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label>หมายเลขห้อง</label>
                        <input type="text" name="room_number"
                            value="{{ old('room_number') }}"
                            placeholder="เช่น A301" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ชั้น</label>
                        <input type="number" name="floor"
                            value="{{ old('floor') }}"
                            min="1" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ประเภทห้อง</label>
                        <select name="room_type" required>
                            <option value="Standard" @selected(old('room_type')==='Standard' )>Standard</option>
                            <option value="Deluxe" @selected(old('room_type')==='Deluxe' )>Deluxe</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าเช่าต่อเดือน (บาท)</label>
                        <input type="number" name="monthly_rent"
                            value="{{ old('monthly_rent') }}"
                            min="0" step="0.01" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าน้ำ (บาท)</label>
                        <input type="number" name="water_rate"
                            value="{{ old('water_rate', 0) }}"
                            min="0" step="0.01" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าไฟ (บาท)</label>
                        <input type="number" name="electric_rate"
                            value="{{ old('electric_rate', 0) }}"
                            min="0" step="0.01" required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>สถานะห้อง</label>
                        <select name="status" required>
                            <option value="available">ว่าง</option>
                            <option value="occupied">มีผู้เช่า</option>
                            <option value="repair">ปิดซ่อม</option>
                        </select>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>รายละเอียดเพิ่มเติม</label>
                        <textarea name="description" rows="3"
                            placeholder="รายละเอียดห้องพัก">{{ old('description') }}</textarea>
                    </div>

                    <button type="submit">บันทึกห้องพัก</button>

                    <a href="{{ route('admin.rooms.index') }}"
                        style="margin-left: 15px;">
                        ยกเลิก
                    </a>
                </form>

            </div>

        </main>
    </div>

</body>

</html>