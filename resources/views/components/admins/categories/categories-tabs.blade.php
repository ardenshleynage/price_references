<div class="container">
    <div class="tabs">
        <a href="{{ route('admins_categories') }}"
            class="tab {{ request()->routeIs('admins_categories') ? 'active' : '' }}">
            Tous
        </a>

        <a href="{{ route('admins_categories_active') }}"
            class="tab {{ request()->routeIs('admins_categories_active') ? 'active' : '' }}">
            Actif
        </a>


        <a href="{{ route('admins_categories_deleted') }}"
            class="tab {{ request()->routeIs('admins_categories_deleted') ? 'active' : '' }}">
            Corbeille
        </a>

        <span class="glider"></span>
    </div>
</div>
{{ $slot }}
