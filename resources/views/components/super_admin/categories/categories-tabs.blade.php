<div class="container">
    <div class="tabs-wrapper">
        <div class="tabs">
            <a href="{{ route('super_admin_categories') }}"
                class="tab {{ request()->routeIs('super_admin_categories') ? 'active' : '' }}">
                Tous
            </a>

            <a href="{{ route('super_admin_categories_active') }}"
                class="tab {{ request()->routeIs('super_admin_categories_active') ? 'active' : '' }}">
                Actif
            </a>

            <a href="{{ route('super_admin_categories_blocked') }}"
                class="tab {{ request()->routeIs('super_admin_categories_blocked') ? 'active' : '' }}">
                Bloqué
            </a>

            <a href="{{ route('super_admin_categories_deleted') }}"
                class="tab {{ request()->routeIs('super_admin_categories_deleted') ? 'active' : '' }}">
                Corbeille
            </a>

            <span class="glider"></span>
        </div>
    </div>
</div>
{{ $slot }}
