<nav>
    <i class='bx bx-menu bx-sm'></i>
    <form action="{{ route('super_admin_search') }}" method="GET">
        <div class="form-input">
            <input type="search" name="q" placeholder="Recherche..." value="{{ request('q') }}">
            <button type="submit" class="search-btn"><i class='bx bx-search'></i></button>
        </div>
    </form>
    <p id="welcome-text">Bienvenue, {{ $loggedUser->username ?? '' }}</p>

    <!-- Profile Menu -->
    <div class="profile-menu" id="profileMenu">
        <ul>
            <li><a href="{{ route('super_admin_profile') }}">Mon profil</a></li>
            <li><a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Déconnexion</a>
            </li>
        </ul>
    </div>
    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
        @csrf
    </form>
</nav>
{{ $slot }}
