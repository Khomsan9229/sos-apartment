<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>จัดการค่าใช้จ่าย - SOS อพาร์ตเมนต์</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .expense-summary {
            background: #ffffff;
            padding: 25px;
            border-radius: 12px;
            margin: 20px 0;
            border: 1px solid #e5e7eb;
        }

        .expense-summary h2 {
            margin-bottom: 10px;
            font-size: 18px;
        }

        .expense-total {
            font-size: 30px;
            font-weight: bold;
            color: #dc2626;
        }

        .expense-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
            margin-bottom: 15px;
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

        .expense-table-wrapper {
            width: 100%;
            overflow-x: auto;
        }

        .expense-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .expense-table th,
        .expense-table td {
            padding: 12px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .expense-table th {
            background: #f3f4f6;
        }

        .expense-table tbody tr:hover {
            background: #f9fafb;
        }

        .expense-empty {
            text-align: center !important;
            color: #6b7280;
            padding: 30px !important;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
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

        .btn-edit-expense {
            display: inline-block;
            background: #f59e0b;
            color: #ffffff;
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-edit-expense:hover {
            background: #d97706;
        }

        .btn-delete-expense {
            background: #dc2626;
            color: #ffffff;
            padding: 8px 14px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
        }

        .btn-delete-expense:hover {
            background: #b91c1c;
        }

        .expense-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .expense-actions form {
            margin: 0;
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

            <h1>จัดการค่าใช้จ่าย</h1>

            <p>รายการค่าใช้จ่ายทั้งหมดของ SOS อพาร์ตเมนต์</p>

            @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
            @endif

            <!-- Expense Summary -->
            <div class="expense-summary">

                <h2>ค่าใช้จ่ายรวมทั้งหมด</h2>

                <div class="expense-total">
                    {{ number_format($totalExpenses, 2) }} บาท
                </div>

            </div>

            <!-- Expense List -->
            <div class="dashboard-section">

                <div class="expense-header">

                    <h2>รายการค่าใช้จ่าย</h2>

                    <a href="{{ route('admin.expenses.create') }}"
                        class="btn-add-expense">
                        + เพิ่มค่าใช้จ่าย
                    </a>

                </div>

                <div class="expense-table-wrapper">

                    <table class="expense-table">

                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                <th>วันที่</th>
                                <th>รายการ</th>
                                <th>หมวดหมู่</th>
                                <th>รายละเอียด</th>
                                <th>จำนวนเงิน</th>
                                <th>จัดการ</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($expenses as $expense)

                            <tr>
                                <td>{{ $loop->iteration }}</td>

                                <td>
                                    {{ $expense->expense_date
                                            ? \Carbon\Carbon::parse($expense->expense_date)->format('d/m/Y')
                                            : '-' }}
                                </td>

                                <td>{{ $expense->title }}</td>

                                <td>{{ $expense->category ?: '-' }}</td>

                                <td>{{ $expense->description ?: '-' }}</td>


                                <td>
                                    {{ number_format($expense->amount, 2) }} บาท
                                </td>


                                <td>
                                    <div class="expense-actions">

                                        <!-- ปุ่มแก้ไข -->
                                        <a href="{{ route('admin.expenses.edit', $expense->id) }}"
                                            class="btn-edit-expense">
                                            แก้ไข
                                        </a>

                                        <!-- ปุ่มลบ -->
                                        <form
                                            action="{{ route('admin.expenses.destroy', $expense->id) }}"
                                            method="POST"
                                            onsubmit="return confirm('คุณต้องการลบรายการค่าใช้จ่ายนี้ใช่หรือไม่? การลบไม่สามารถย้อนกลับได้');">
                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn-delete-expense">
                                                ลบ
                                            </button>
                                        </form>

                                    </div>
                                </td>


                            </tr>

                            @empty

                            <tr>
                                <td colspan="7" class="expense-empty">
                                    ยังไม่มีข้อมูลค่าใช้จ่าย
                                </td>
                            </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </main>

    </div>

</body>

</html>