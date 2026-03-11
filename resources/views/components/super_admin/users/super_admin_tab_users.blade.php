<div class="container">
    <div class="tabs">
        <a href="{{ route('super_admin_users') }}"
            class="tab {{ request()->routeIs('super_admin_users') ? 'active' : '' }}">
            Tous
        </a>

        <a href="{{ route('super_admin_users_active') }}"
            class="tab {{ request()->routeIs('super_admin_users_active') ? 'active' : '' }}">
            Actif
        </a>

        <a href="{{ route('super_admin_users_blocked') }}"
            class="tab {{ request()->routeIs('super_admin_users_blocked') ? 'active' : '' }}">
            Bloqué
        </a>

        <a href="{{ route('super_admin_users_deleted') }}"
            class="tab {{ request()->routeIs('super_admin_users_deleted') ? 'active' : '' }}">
            Corbeille
        </a>

        <span class="glider"></span>
    </div>
</div>
{{ $slot }}
