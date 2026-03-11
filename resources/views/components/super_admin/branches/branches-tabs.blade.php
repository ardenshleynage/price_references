<div class="container">
    <div class="tabs">
        <a href="{{ route('super_admin_branches') }}"
            class="tab {{ request()->routeIs('super_admin_branches') ? 'active' : '' }}">
            Tous
        </a>

        <a href="{{ route('super_admin_branches_active') }}"
            class="tab {{ request()->routeIs('super_admin_branches_active') ? 'active' : '' }}">
            Actif
        </a>

        <a href="{{ route('super_admin_branches_blocked') }}"
            class="tab {{ request()->routeIs('super_admin_branches_blocked') ? 'active' : '' }}">
            Bloqué
        </a>

        <a href="{{ route('super_admin_branches_deleted') }}"
            class="tab {{ request()->routeIs('super_admin_branches_deleted') ? 'active' : '' }}">
            Corbeille
        </a>

        <span class="glider"></span>
    </div>
</div>
{{ $slot }}
