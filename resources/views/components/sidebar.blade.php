@php
    $user = Auth::user();
    $role = $user ? (int) $user->role : 3;
    $homeRoute = match ($role) {
        1 => 'super_admin_home',
        2 => 'admins_home',
        3 => 'readers_home',
        default => 'login',
    };
    $catRoute = match ($role) {
        1 => 'super_admin_categories',
        2 => 'admins_categories',
        3 => 'readers_categories',
        default => 'login',
    };
    $branchRoute = match ($role) {
        1 => 'super_admin_branches',
        2 => 'admins_branches',
        3 => 'readers_branches',
        default => 'login',
    };
    $productRoute = match ($role) {
        1 => 'super_admin_products',
        2 => 'admins_products',
        3 => 'readers_products',
        default => 'login',
    };
    $usersRoute = match ($role) {
        1 => 'super_admin_users',
        default => null,
    };
    $profileRoute = match ($role) {
        1 => 'super_admin_profile',
        2 => 'admins_profile',
        3 => 'readers_profile',
        default => 'login',
    };
@endphp

<section id="sidebar">
    <a href="{{ route($homeRoute) }}" class="brand">
        <i class='bx bxs-smile bx-lg'></i>
        <span class="text">AdminHub</span>
    </a>
    <ul class="side-menu top">
        <li class="{{ request()->routeIs('*_home') ? 'active' : '' }}">
            <a href="{{ route($homeRoute) }}">
                <i class='bx bxs-dashboard bx-sm'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('*_products') ? 'active' : '' }}">
            <a href="{{ route($productRoute) }}">
                <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                <span class="text">Produits</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('*_categories') ? 'active' : '' }}">
            <a href="{{ route($catRoute) }}">
                <i class='bx bxs-doughnut-chart bx-sm'></i>
                <span class="text">Catégories</span>
            </a>
        </li>
        <li class="{{ request()->routeIs('*_branches') ? 'active' : '' }}">
            <a href="{{ route($branchRoute) }}">
                <i class='bx bxs-business bx-sm'></i>
                <span class="text">Branches</span>
            </a>
        </li>
        @if ($usersRoute)
            <li class="{{ request()->routeIs('*_users') ? 'active' : '' }}">
                <a href="{{ route($usersRoute) }}">
                    <i class='bx bxs-group bx-sm'></i>
                    <span class="text">Utilisateurs</span>
                </a>
            </li>
        @endif
    </ul>
    <ul class="side-menu bottom">
        <li class="{{ request()->routeIs('*_profile') ? 'active' : '' }}">
            <a href="{{ route($profileRoute) }}">
                <i class='bx bxs-user-circle bx-sm'></i>
                <span class="text">Mon profil</span>
            </a>
        </li>
        <li>
            <a href="#" class="logout" onclick="openLogoutModal(event)">
                <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                <span class="text">Déconnexion</span>
            </a>
        </li>
    </ul>

    <style>
        .logout-text {
            color: #555;
            text-align: center;
        }

        html.dark .logout-text {
            color: #ccc;
        }

        .login-btn {
            width: 100%;
            padding: 10px;
            background: #163172;
            color: #f6f6f6;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.3s;
        }

        .login-btn:hover {
            background: #1e3e7a;
        }

        .cancel-btn {
            background: #6c757d;
        }

        .cancel-btn:hover {
            background: #5a6268;
        }

        html.dark .login-btn {
            background: #3a6bc5;
        }

        html.dark .login-btn:hover {
            background: #4d7fd6;
        }

        html.dark .cancel-btn {
            background: #495057;
        }

        html.dark .cancel-btn:hover {
            background: #5a6268;
        }
    </style>

    <div id="logoutModal" class="modal-overlay" onclick="closeLogoutModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeLogoutModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>

            <h2 class="login-header">Déconnexion</h2>

            <form class="login-container" method="POST" action="{{ route('logout') }}">
                @csrf
                <p style="text-align: center;" class="logout-text">Êtes-vous sûr de vouloir vous déconnecter ?</p>
                <p><button type="submit" class="login-btn">Oui, me déconnecter</button></p>
                <p><button type="button" class="login-btn cancel-btn" onclick="closeLogoutModal()">Annuler</button></p>
            </form>
        </div>
    </div>

    <script>
        function openLogoutModal(event) {
            if (event) event.preventDefault();
            document.getElementById('logoutModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }

        function closeLogoutModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('logoutModal').classList.remove('active');
            document.body.style.overflow = '';
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
</section>
{{ $slot }}
