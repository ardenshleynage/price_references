<div class="container">
    <div class="tabs">
        <a href="{{ route('admins_products') }}" class="tab {{ request()->routeIs('admins_products') ? 'active' : '' }}">
            Tous
        </a>

        <a href="{{ route('admins_products_active') }}"
            class="tab {{ request()->routeIs('admins_products_active') ? 'active' : '' }}">
            Actif
        </a>

        <a href="{{ route('admins_products_deleted') }}"
            class="tab {{ request()->routeIs('admins_products_deleted') ? 'active' : '' }}">
            Corbeille
        </a>

        <span class="glider"></span>
    </div>
</div>
{{ $slot }}
