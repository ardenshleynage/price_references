<div class="container">
    <div class="tabs-wrapper">
        <div class="tabs">
            <a href="{{ route('super_admin_products') }}"
                class="tab {{ request()->routeIs('super_admin_products') ? 'active' : '' }}">
                Tous
            </a>

            <a href="{{ route('super_admin_products_active') }}"
                class="tab {{ request()->routeIs('super_admin_products_active') ? 'active' : '' }}">
                Actif
            </a>

            <a href="{{ route('super_admin_products_blocked') }}"
                class="tab {{ request()->routeIs('super_admin_products_blocked') ? 'active' : '' }}">
                Bloqué
            </a>

            <a href="{{ route('super_admin_products_deleted') }}"
                class="tab {{ request()->routeIs('super_admin_products_deleted') ? 'active' : '' }}">
                Corbeille
            </a>

            <span class="glider"></span>
        </div>
    </div>
</div>
{{ $slot }}
