<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>การแจ้งเตือน - SOS อพาร์ตเมนต์</title>

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    <style>
        .notification-read {
            background-color: #ffffff;
        }

        .notification-unread {
            background-color: #eff6ff;
        }

        .notification-item {
            padding: 18px;
            margin-bottom: 15px;
            border-radius: 10px;
            border: 1px solid #e5e7eb;
        }

        .notification-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 10px;
        }

        .notification-header h3 {
            margin: 0;
        }

        .notification-message {
            margin-top: 12px;
        }

        .notification-date {
            color: #6b7280;
        }

        .notification-badge {
            background: #2563eb;
            color: white;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            white-space: nowrap;
        }

        .notification-read-label {
            color: #6b7280;
            font-size: 12px;
        }

        .notification-count {
            background: #dc2626;
            color: white;
            padding: 3px 8px;
            border-radius: 12px;
            font-size: 12px;
            margin-left: 8px;
        }

        .read-button {
            background: #2563eb;
            color: white;
            border: none;
            padding: 9px 16px;
            border-radius: 6px;
            cursor: pointer;
        }

        .read-button:hover {
            background: #1d4ed8;
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

        .empty-notification {
            text-align: center;
            padding: 30px;
            color: #6b7280;
        }

        .success-message {
            background: #dcfce7;
            color: #166534;
            padding: 12px;
            border-radius: 8px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

    @php
    $unreadCount = \App\Models\Notification::where('user_id', auth()->id())
    ->where('is_read', 0)
    ->count();
    @endphp

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

            <h1>การแจ้งเตือน</h1>
            <p>รายการแจ้งเตือนจากระบบ SOS อพาร์ตเมนต์</p>

            @if (session('success'))
            <div class="success-message">
                {{ session('success') }}
            </div>
            @endif

            <div class="dashboard-section">

                <h2>รายการแจ้งเตือนของฉัน</h2>

                <div style="margin-top: 20px;">

                    @forelse ($notifications as $notification)

                    <div class="notification-item {{ $notification->is_read ? 'notification-read' : 'notification-unread' }}">

                        <div class="notification-header">

                            <h3>
                                {{ $notification->title }}
                            </h3>

                            @if (!$notification->is_read)

                            <span class="notification-badge">
                                ยังไม่อ่าน
                            </span>

                            @else

                            <span class="notification-read-label">
                                อ่านแล้ว
                            </span>

                            @endif

                        </div>

                        <p class="notification-message">
                            {{ $notification->message }}
                        </p>

                        <small class="notification-date">
                            {{ $notification->created_at
                                    ? $notification->created_at->format('d/m/Y H:i')
                                    : '-' }}
                        </small>

                        @if (!$notification->is_read)

                        <form
                            action="{{ route('tenant.notifications.read', $notification->id) }}"
                            method="POST"
                            style="margin-top: 15px;">

                            @csrf

                            <button type="submit" class="read-button">
                                อ่านแล้ว
                            </button>

                        </form>

                        @endif

                    </div>

                    @empty

                    <p class="empty-notification">
                        ยังไม่มีการแจ้งเตือน
                    </p>

                    @endforelse

                </div>

            </div>

        </main>

    </div>

</body>

</html>