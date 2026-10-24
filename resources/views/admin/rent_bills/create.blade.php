<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สร้างบิลค่าเช่า - SOS อพาร์ตเมนต์</title>
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

            <h1>สร้างบิลค่าเช่า</h1>
            <p>กรอกข้อมูลเพื่อออกบิลค่าเช่าให้ผู้เช่า</p>

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

                <form action="{{ route('admin.rent_bills.store') }}"
                    method="POST">

                    @csrf

                    <div style="margin-bottom: 15px;">
                        <label>เลือกผู้เช่า</label>

                        <select name="tenant_id" id="tenant_id" required>
                            <option value="">-- เลือกผู้เช่า --</option>

                            @foreach ($tenants as $tenant)
                            <option
                                value="{{ $tenant->id }}"
                                data-rent="{{ $tenant->room?->monthly_rent ?? 0 }}"
                                @selected(old('tenant_id')==$tenant->id)
                                >
                                {{ $tenant->user?->fullname ?? '-' }}
                                (ห้อง {{ $tenant->room?->room_number ?? '-' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>เดือนที่เรียกเก็บ</label>
                        <input type="month"
                            name="billing_month"
                            value="{{ old('billing_month') }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าเช่าห้อง (บาท)</label>
                        <input type="number"
                            name="room_rent"
                            id="room_rent"
                            step="0.01"
                            min="0"
                            value="{{ old('room_rent', 0) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าน้ำ (บาท)</label>
                        <input type="number"
                            name="water_cost"
                            id="water_cost"
                            step="0.01"
                            min="0"
                            value="{{ old('water_cost', 0) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าไฟ (บาท)</label>
                        <input type="number"
                            name="electric_cost"
                            id="electric_cost"
                            step="0.01"
                            min="0"
                            value="{{ old('electric_cost', 0) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>ค่าใช้จ่ายอื่น ๆ (บาท)</label>
                        <input type="number"
                            name="other_cost"
                            id="other_cost"
                            step="0.01"
                            min="0"
                            value="{{ old('other_cost', 0) }}"
                            required>
                    </div>

                    <div style="margin-bottom: 15px;">
                        <label>วันครบกำหนดชำระ</label>
                        <input type="date"
                            name="due_date"
                            value="{{ old('due_date') }}">
                    </div>

                    <div style="margin-bottom: 20px;">
                        <label>ยอดรวมทั้งหมด (บาท)</label>
                        <input type="text"
                            id="total_amount"
                            value="0.00"
                            readonly>
                    </div>

                    <button type="submit"
                        style="background: #2563eb;
                               color: white;
                               border: none;
                               padding: 10px 18px;
                               border-radius: 8px;
                               cursor: pointer;">
                        บันทึกบิลค่าเช่า
                    </button>

                    <a href="{{ route('admin.rent_bills.index') }}"
                        style="margin-left: 15px;">
                        ยกเลิก
                    </a>

                </form>

            </div>
        </main>
    </div>

    <script>
        const tenantSelect = document.getElementById('tenant_id');

        const roomRent = document.getElementById('room_rent');
        const waterCost = document.getElementById('water_cost');
        const electricCost = document.getElementById('electric_cost');
        const otherCost = document.getElementById('other_cost');
        const totalAmount = document.getElementById('total_amount');

        function calculateTotal() {
            const total =
                Number(roomRent.value || 0) +
                Number(waterCost.value || 0) +
                Number(electricCost.value || 0) +
                Number(otherCost.value || 0);

            totalAmount.value = total.toFixed(2);
        }

        tenantSelect.addEventListener('change', function() {
            const selected = tenantSelect.selectedOptions[0];

            roomRent.value = selected?.dataset.rent || 0;
            calculateTotal();
        });

        [roomRent, waterCost, electricCost, otherCost].forEach(input => {
            input.addEventListener('input', calculateTotal);
        });

        calculateTotal();
    </script>

</body>

</html>