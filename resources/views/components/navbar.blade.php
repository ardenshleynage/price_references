<nav>
    <i class='bx bx-menu bx-sm'></i>
    <form
        action="{{ $loggedUser->role == 1 ? route('super_admin_search') : ($loggedUser->role == 2 ? route('admins_search') : route('readers_search')) }}"
        method="GET">
        <div class="form-input">
            <input type="search" name="q" placeholder="Recherche..." value="{{ request('q') }}">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>
    <p id="welcome-text">Bienvenue, {{ $loggedUser->username ?? '' }}</p>

    <!-- Profile Menu -->
    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a
                    href="{{ $loggedUser->role == 1 ? route('super_admin_profile') : ($loggedUser->role == 2 ? route('admins_profile') : route('readers_profile')) }}">Mon
                    profil</a></li>
            <li>
                <a id="logout-form" class="logout" href="#" onclick="openLogoutModal(event)">
                    <span class="text">Déconnexion</span>
                </a>

            </li>
        </ul>
    </div>
</nav>
{{ $slot }}
