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
        $sidebarUnreadCount = \App\Models\Notification::where('user_id', auth()->id())
        ->where('is_read', 0)
        ->count();
        @endphp

        <a href="{{ route('tenant.notifications.index') }}"
            class="{{ request()->is('tenant/notifications*') ? 'active' : '' }}">
            การแจ้งเตือน

            @if ($sidebarUnreadCount > 0)
            <span style="
                    background: #dc2626;
                    color: white;
                    padding: 3px 8px;
                    border-radius: 12px;
                    font-size: 12px;
                    margin-left: 8px;
                ">
                {{ $sidebarUnreadCount }}
            </span>
            @endif
        </a>

        <form method="POST"
            action="{{ route('logout') }}"
            style="margin-top: 20px;">
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
                    font-size: 14px;
                ">
                ออกจากระบบ
            </button>
        </form>

    </nav>

</aside>