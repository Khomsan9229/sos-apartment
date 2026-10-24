<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>เพิ่มค่าใช้จ่าย - SOS อพาร์ตเมนต์</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .expense-form {
            background: #ffffff;
            padding: 30px;
            border-radius: 12px;
            max-width: 700px;
            margin-top: 20px;
            border: 1px solid #e5e7eb;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-weight: 600;
            margin-bottom: 8px;
        }

        .form-control {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            font-size: 15px;
            box-sizing: border-box;
        }

        .form-control:focus {
            outline: none;
            border-color: #2563eb;
        }

        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn-save {
            background: #16a34a;
            color: white;
            border: none;
            padding: 12px 22px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 15px;
        }

        .btn-cancel {
            background: #6b7280;
            color: white;
            padding: 12px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
        }

        .error-box {
            background: #fee2e2;
            color: #991b1b;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }

        .field-error {
            color: #dc2626;
            font-size: 13px;
            margin-top: 5px;
        }

        .logout-button {
            width: 100%;
            background: #dc2626;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 8px;
            cursor: pointer;
            text-align: left;
        }

        .btn-add-expense {
            display: inline-block;
            background: #2563eb;
            color: #ffffff;
            padding: 12px 20px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 15px;
            font-weight: 600;
        }

        .btn-add-expense:hover {
            background: #1d4ed8;
        }

        .expense-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
        }
    </style>

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

            <h1>เพิ่มรายการค่าใช้จ่าย</h1>

            <p>กรอกข้อมูลค่าใช้จ่ายของอพาร์ตเมนต์</p>

            <div class="expense-form">

                @if ($errors->any())
                <div class="error-box">
                    <strong>กรุณาตรวจสอบข้อมูล</strong>

                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <form action="{{ route('admin.expenses.store') }}"
                    method="POST">

                    @csrf

                    <!-- ชื่อรายการ -->
                    <div class="form-group">
                        <label for="title">ชื่อรายการค่าใช้จ่าย *</label>

                        <input
                            type="text"
                            id="title"
                            name="title"
                            class="form-control"
                            value="{{ old('title') }}"
                            placeholder="เช่น ค่าซ่อมเครื่องปรับอากาศ"
                            maxlength="255"
                            required>

                        @error('title')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- หมวดหมู่ -->
                    <div class="form-group">
                        <label for="category">หมวดหมู่ *</label>

                        <select
                            id="category"
                            name="category"
                            class="form-control"
                            required>
                            <option value="">-- เลือกหมวดหมู่ --</option>

                            <option value="ค่าน้ำ"
                                {{ old('category') == 'ค่าน้ำ' ? 'selected' : '' }}>
                                ค่าน้ำ
                            </option>

                            <option value="ค่าไฟ"
                                {{ old('category') == 'ค่าไฟ' ? 'selected' : '' }}>
                                ค่าไฟ
                            </option>

                            <option value="ซ่อมบำรุง"
                                {{ old('category') == 'ซ่อมบำรุง' ? 'selected' : '' }}>
                                ซ่อมบำรุง
                            </option>

                            <option value="อุปกรณ์"
                                {{ old('category') == 'อุปกรณ์' ? 'selected' : '' }}>
                                อุปกรณ์
                            </option>

                            <option value="อื่น ๆ"
                                {{ old('category') == 'อื่น ๆ' ? 'selected' : '' }}>
                                อื่น ๆ
                            </option>
                        </select>

                        @error('category')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- จำนวนเงิน -->
                    <div class="form-group">
                        <label for="amount">จำนวนเงิน (บาท) *</label>

                        <input
                            type="number"
                            id="amount"
                            name="amount"
                            class="form-control"
                            value="{{ old('amount') }}"
                            min="0.01"
                            step="0.01"
                            placeholder="0.00"
                            required>

                        @error('amount')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- วันที่ -->
                    <div class="form-group">
                        <label for="expense_date">วันที่จ่าย *</label>

                        <input
                            type="date"
                            id="expense_date"
                            name="expense_date"
                            class="form-control"
                            value="{{ old('expense_date', date('Y-m-d')) }}"
                            required>

                        @error('expense_date')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- รายละเอียด -->
                    <div class="form-group">
                        <label for="description">รายละเอียดเพิ่มเติม</label>

                        <textarea
                            id="description"
                            name="description"
                            class="form-control"
                            placeholder="รายละเอียดค่าใช้จ่าย (ถ้ามี)">{{ old('description') }}</textarea>

                        @error('description')
                        <div class="field-error">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Buttons -->
                    <div class="form-actions">

                        <button type="submit" class="btn-save">
                            บันทึกค่าใช้จ่าย
                        </button>

                        <a href="{{ route('admin.expenses.index') }}"
                            class="btn-cancel">
                            ยกเลิก
                        </a>

                    </div>

                </form>

            </div>

        </main>

    </div>

</body>

</html>