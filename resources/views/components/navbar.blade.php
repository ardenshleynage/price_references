@php
    $loggedUser = Auth::user();
    $role = $loggedUser ? (int) $loggedUser->role : 3;
    $profileRoute = match ($role) {
        1 => 'super_admin_profile',
        2 => 'admins_profile',
        3 => 'readers_profile',
        default => 'login',
    };
    $searchRoute = match ($role) {
        1 => 'super_admin_search',
        2 => 'admins_search',
        3 => 'readers_search',
        default => 'login',
    };
@endphp
<nav>
    <i class='bx bx-menu bx-sm'></i>
    <form action="{{ route($searchRoute) }}" method="GET">
        <div class="form-input">
            <input type="search" name="q" placeholder="Recherche..." value="{{ request('q') }}">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>
    <p id="welcome-text">Bienvenue, {{ $loggedUser->username ?? '' }}</p>

    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a href="{{ route($profileRoute) }}">Mon profil</a></li>
            <li>
                <a id="logout-form" class="logout" href="#" onclick="openLogoutModal(event)">
                    <span class="text">Déconnexion</span>
                </a>
            </li>
        </ul>
    </div>
</nav>
{{ $slot }}
