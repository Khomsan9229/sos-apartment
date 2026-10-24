<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขผู้เช่า - SOS อพาร์ตเมนต์</title>
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

            <h1>แก้ไขข้อมูลผู้เช่า</h1>
            <p>แก้ไขรายละเอียดของผู้เช่าในระบบ</p>

            <div class="dashboard-section">

                @if ($errors->any())
                <div style="color: #ef4444; margin-bottom: 20px;">
                    <strong>กรุณาตรวจสอบข้อมูล</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.tenants.update', $tenant->id) }}"
                    method="POST">

                    @csrf
                    @method('PUT')

                    <div style="margin-bottom: 15px;">
                        <label>ชื่อ-นามสกุล</label>
                        <input type="text"
                            name="fullname"
                            value="{{ old('fullname', $tenant->user?->fullname) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ชื่อผู้ใช้ (Username)</label>
                        <input type="text"
                            name="username"
                            value="{{ old('username', $tenant->user?->username) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>เบอร์โทรศัพท์</label>
                        <input type="text"
                            name="phone"
                            value="{{ old('phone', $tenant->phone) }}">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>เลขบัตรประชาชน</label>
                        <input type="text"
                            name="id_card"
                            maxlength="13"
                            value="{{ old('id_card', $tenant->id_card) }}">
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ห้องพัก</label>
                        <select name="room_id" required>
                            <option value="">-- เลือกห้องพัก --</option>

                            @foreach ($rooms as $room)
                            <option value="{{ $room->id }}"
                                @selected(old('room_id', $tenant->room_id) == $room->id)>
                                {{ $room->room_number }}
                                ({{ number_format($room->monthly_rent, 2) }} บาท)
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>วันที่เข้าพัก</label>
                        <input type="date"
                            name="move_in_date"
                            value="{{ old('move_in_date', $tenant->move_in_date) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>สถานะผู้เช่า</label>
                        <select name="status" required>
                            <option value="active"
                                @selected(old('status', $tenant->status) === 'active')>
                                กำลังพักอาศัย
                            </option>

                            <option value="moved_out"
                                @selected(old('status', $tenant->status) === 'moved_out')>
                                ย้ายออกแล้ว
                            </option>
                        </select>
                    </div>

                    <button type="submit"
                        style="background: #2563eb;
                               color: white;
                               border: none;
                               padding: 10px 18px;
                               border-radius: 8px;
                               cursor: pointer;">
                        บันทึกการแก้ไข
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