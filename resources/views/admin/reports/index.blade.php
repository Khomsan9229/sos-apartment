<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>รายงาน - SOS อพาร์ตเมนต์</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .report-header {
            margin-bottom: 25px;
        }

        .report-header h1 {
            margin-bottom: 8px;
        }

        .report-header p {
            color: #6b7280;
        }

        .report-cards {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
            margin-top: 25px;
            margin-bottom: 25px;
        }

        .report-card {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            min-width: 0;
        }

        .report-card h3 {
            font-size: 16px;
            margin: 0 0 15px;
            color: #374151;
        }

        .report-card .amount {
            font-size: 30px;
            font-weight: bold;
            overflow-wrap: anywhere;
        }

        .income {
            color: #16a34a;
        }

        .expense {
            color: #dc2626;
        }

        .balance {
            color: #2563eb;
        }

        .unpaid {
            color: #d97706;
        }

        .report-section {
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            padding: 25px;
            margin-top: 20px;
        }

        .report-section h2 {
            font-size: 20px;
            margin-bottom: 15px;
        }

        .report-table {
            width: 100%;
            border-collapse: collapse;
        }

        .report-table th,
        .report-table td {
            padding: 14px;
            border-bottom: 1px solid #e5e7eb;
            text-align: left;
        }

        .report-table th {
            background: #f3f4f6;
        }

        .report-table td:last-child {
            text-align: right;
            font-weight: 600;
        }

        .report-table th:last-child {
            text-align: right;
        }

        .report-note {
            margin-top: 15px;
            font-size: 14px;
            color: #6b7280;
            line-height: 1.7;
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

        @media (max-width: 768px) {
            .report-cards {
                grid-template-columns: 1fr;
            }

            .report-card .amount {
                font-size: 25px;
            }
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

            <div class="report-header">
                <h1>รายงานภาพรวมอพาร์ตเมนต์</h1>
                <p>สรุปรายรับ รายจ่าย และสถานะการชำระค่าเช่า</p>
            </div>

            <!-- ตัวกรองรายงาน -->
            <div class="report-section" style="margin-bottom: 20px;">

                <h2>เลือกเดือนและปีที่ต้องการดูรายงาน</h2>

                <form method="GET"
                    action="{{ route('admin.reports.index') }}"
                    style="display: flex; gap: 15px; flex-wrap: wrap; align-items: end;">

                    <div>
                        <label>เดือน</label>

                        <select name="month"
                            style="display: block; padding: 10px; margin-top: 8px;">

                            @foreach ([
                            1 => 'มกราคม',
                            2 => 'กุมภาพันธ์',
                            3 => 'มีนาคม',
                            4 => 'เมษายน',
                            5 => 'พฤษภาคม',
                            6 => 'มิถุนายน',
                            7 => 'กรกฎาคม',
                            8 => 'สิงหาคม',
                            9 => 'กันยายน',
                            10 => 'ตุลาคม',
                            11 => 'พฤศจิกายน',
                            12 => 'ธันวาคม'
                            ] as $number => $name)

                            <option value="{{ $number }}"
                                {{ $month == $number ? 'selected' : '' }}>
                                {{ $name }}
                            </option>

                            @endforeach

                        </select>
                    </div>

                    <div>
                        <label>ปี (ค.ศ.)</label>

                        <select name="year"
                            style="display: block; padding: 10px; margin-top: 8px;">

                            @for ($y = now()->year; $y >= 2020; $y--)
                            <option value="{{ $y }}"
                                {{ $year == $y ? 'selected' : '' }}>
                                {{ $y }}
                            </option>
                            @endfor

                        </select>
                    </div>

                    <button type="submit"
                        style="
                    padding: 11px 22px;
                    background: #2563eb;
                    color: white;
                    border: none;
                    border-radius: 8px;
                    cursor: pointer;
                ">
                        ดูรายงาน
                    </button>

                </form>

            </div>


            <!-- กราฟรายรับและรายจ่าย -->
            <div class="report-section">

                <h2>กราฟรายรับ–รายจ่ายรายวัน</h2>

                <div style="position: relative; height: 350px;">
                    <canvas id="incomeExpenseChart"></canvas>
                </div>

            </div>

            <!-- Summary Cards -->
            <div class="report-cards">

                <div class="report-card">
                    <h3>รายรับค่าเช่าทั้งหมด</h3>

                    <div class="amount income">
                        {{ number_format($totalIncome, 2) }} บาท
                    </div>
                </div>

                <div class="report-card">
                    <h3>รายจ่ายทั้งหมด</h3>

                    <div class="amount expense">
                        {{ number_format($totalExpenses, 2) }} บาท
                    </div>
                </div>

                <div class="report-card">
                    <h3>ผลต่างรายรับ - รายจ่าย</h3>

                    <div class="amount balance"
                        @if ($balance < 0)
                        style="color: #dc2626;"
                        @endif>
                        {{ number_format($balance, 2) }} บาท
                    </div>
                </div>

                <div class="report-card">
                    <h3>ยอดบิลที่ยังไม่ได้ชำระ</h3>

                    <div class="amount unpaid">
                        {{ number_format($totalUnpaid, 2) }} บาท
                    </div>
                </div>

            </div>

            <!-- Summary Table -->
            <div class="report-section">

                <h2>ตารางสรุปข้อมูลทางการเงิน</h2>

                <table class="report-table">

                    <thead>
                        <tr>
                            <th>รายการ</th>
                            <th>จำนวนเงิน (บาท)</th>
                        </tr>
                    </thead>

                    <tbody>
                        <tr>
                            <td>รายรับจากการชำระเงินที่อนุมัติแล้ว</td>
                            <td>
                                {{ number_format($totalIncome, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>รายจ่ายทั้งหมด</td>
                            <td>
                                {{ number_format($totalExpenses, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>ผลต่างรายรับ - รายจ่าย</td>
                            <td>
                                {{ number_format($balance, 2) }}
                            </td>
                        </tr>

                        <tr>
                            <td>ยอดบิลที่ยังไม่ได้ชำระ</td>
                            <td>
                                {{ number_format($totalUnpaid, 2) }}
                            </td>
                        </tr>
                    </tbody>

                </table>

                <p class="report-note">
                    หมายเหตุ: รายงานนี้แสดงยอดสะสมทั้งหมดจากฐานข้อมูล
                    โดยรายรับนับเฉพาะการชำระเงินที่ได้รับอนุมัติแล้ว
                    และยอดบิลที่ยังไม่ได้ชำระนับเฉพาะสถานะ unpaid
                    ไม่รวมบิลที่กำลังรอตรวจสอบ (pending)
                    ผลต่างรายรับและรายจ่ายไม่ใช่กำไรสุทธิทางบัญชี
                </p>

            </div>

        </main>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <script>
        const chartLabels = @json($chartLabels);
        const chartIncome = @json($chartIncome);
        const chartExpenses = @json($chartExpenses);

        const ctx = document.getElementById('incomeExpenseChart');

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                        label: 'รายรับ (บาท)',
                        data: chartIncome,
                        backgroundColor: '#16a34a'
                    },
                    {
                        label: 'รายจ่าย (บาท)',
                        data: chartExpenses,
                        backgroundColor: '#dc2626'
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true
                    },
                    x: {
                        title: {
                            display: true,
                            text: 'วันที่'
                        }
                    }
                }
            }
        });
    </script>

</body>

</html>