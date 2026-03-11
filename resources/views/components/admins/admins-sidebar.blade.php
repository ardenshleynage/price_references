<section id="sidebar">
    <a href="{{ route('admins_home') }}" class="brand">
        <i class='bx bxs-smile  bx-lg'></i>
        <span class="text">AdminHub</span>
    </a>
    <ul class="side-menu top">
        <li class="{{ request()->routeIs('admins_home') ? 'active' : '' }}">
            <a href="{{ route('admins_home') }}">
                <i class='bx bxs-dashboard bx-sm'></i>
                <span class="text">Dashboard</span>
            </a>
        </li>
        <li
            class="{{ request()->routeIs('admins_products', 'admins_products_active', 'admins_products_deleted') ? 'active' : '' }}">
            <a href="{{ route('admins_products') }}">
                <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                <span class="text">Produits</span>
            </a>
        </li>
        <li
            class="{{ request()->routeIs('admins_categories', 'admins_categories_active', 'admins_categories_deleted') ? 'active' : '' }}">
            <a href="{{ route('admins_categories') }}">
                <i class='bx bxs-category'></i>
                <span class="text">Catégories</span>
            </a>
        </li>

        <li
            class="{{ request()->routeIs('admins_branches', 'admins_branches_active', 'admins_branches_deleted') ? 'active' : '' }}">
            <a href="{{ route('admins_branches') }}">
                <i class='bx bx-buildings bx-sm'></i>
                <span class="text">Branches</span>
            </a>
        </li>

    </ul>
    <ul class="side-menu bottom">
        <li class="{{ request()->routeIs('admins_profile') ? 'active' : '' }}">
            <a href="{{ route('admins_profile') }}">
                <i class='bx bxs-user-circle bx-sm'></i>
                <span class="text">Mon profil</span>
            </a>
        </li>
        <li>
            <a class="logout" href="#" onclick="openLogoutModal(event)">
                <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                <span class="text">Déconnexion</span>
            </a>
        </li>
    </ul>
    <style>
        .login-btn {
            width: 100%;
            padding: 16px;
            border: none;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.95em;
            border-radius: 4px;
        }

        .login-btn[type="submit"] {
            background: #28d;
            color: #fff;
        }

        .login-btn[type="submit"]:hover {
            background: #17c;
        }

        .cancel-btn {
            background: #ebebeb;
            color: #555;
            border: 1px solid #bbb;
        }

        .cancel-btn:hover {
            background: #ddd;
        }

        .logout-text {
            color: #555;
            text-align: center;
        }

        html.dark .logout-text {
            color: #ccc;
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
        // Ouvrir la modal
        function openLogoutModal(event) {
            if (event) event.preventDefault();
            document.getElementById('logoutModal').classList.add('active');
            document.body.style.overflow = 'hidden';
        }
        // Fermer la modal
        function closeLogoutModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('logoutModal').classList.remove('active');
            document.body.style.overflow = '';
        }
        // Fermer avec ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLogoutModal();
            }
        });
    </script>
</section>
{{ $slot }}
