<div class="container">
    <div class="tabs-wrapper">
        <div class="tabs">
            <a href="{{ route('admins_branches') }}" class="tab {{ request()->routeIs('admins_branches') ? 'active' : '' }}">
                Tous
            </a>

            <a href="{{ route('admins_branches_active') }}"
                class="tab {{ request()->routeIs('admins_branches_active') ? 'active' : '' }}">
                Actif
            </a>

            <a href="{{ route('admins_branches_deleted') }}"
                class="tab {{ request()->routeIs('admins_branches_deleted') ? 'active' : '' }}">
                Corbeille
            </a>

            <span class="glider"></span>
        </div>
    </div>
</div>
{{ $slot }}
