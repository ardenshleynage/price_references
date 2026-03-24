<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">
    <x-mobile.navbar />

    <main class="main-content">
        <div class="page-header">
            <h1>Mon Profil</h1>
        </div>

        <div class="loading-state" id="loadingState">
            <div class="spinner-large"></div>
            <p>Chargement...</p>
        </div>

        <div class="not-auth-content" id="notAuthContent" style="display: none;">
            <p>Veuillez vous connecter pour voir votre profil.</p>
            <a href="{{ route('mobile.login') }}" class="btn btn-primary" style="margin-top: 16px;">Se connecter</a>
        </div>

        <div class="authenticated-content" id="authenticatedContent" style="display: none;">
            <div class="profile-header">
                <div class="profile-avatar">
                    <i class='bx bxs-user'></i>
                </div>
                <h2 id="profileUsername">-</h2>
                <span class="role-badge" id="profileRole">-</span>
            </div>

            <div class="profile-card">
                <h3>Informations du compte</h3>

                <div class="profile-row">
                    <div class="profile-label">
                        <i class='bx bxs-user'></i>
                        <span>Nom d'utilisateur</span>
                    </div>
                    <div class="profile-value" id="infoUsername">-</div>
                </div>

                <div class="profile-row">
                    <div class="profile-label">
                        <i class='bx bxs-envelope'></i>
                        <span>E-mail</span>
                    </div>
                    <div class="profile-value" id="infoEmail">-</div>
                </div>

                <div class="profile-row">
                    <div class="profile-label">
                        <i class='bx bxs-shield'></i>
                        <span>Rôle</span>
                    </div>
                    <div class="profile-value" id="infoRole">-</div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Statistiques</h3>
                <div class="stats-grid">
                    <div class="stat-item">
                        <span class="stat-value" id="statProducts">-</span>
                        <span class="stat-label">Produits</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="statCategories">-</span>
                        <span class="stat-label">Catégories</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-value" id="statBranches">-</span>
                        <span class="stat-label">Branches</span>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Thème</h3>
                <div class="theme-row">
                    <div class="theme-label">
                        <i class='bx bxs-moon' id="themeIcon"></i>
                        <span id="themeLabel">Mode clair</span>
                    </div>
                    <label class="theme-switch">
                        <input type="checkbox" id="themeToggle" onchange="toggleTheme()">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <button class="btn btn-danger" onclick="logout()">
                <i class='bx bxs-log-out'></i>
                Se déconnecter
            </button>
        </div>
    </main>

    <x-mobile.bottom-nav />

    <x-mobile.footer />

    <style>
        .theme-switch {
            position: relative;
            display: inline-block;
            width: 52px;
            height: 28px;
        }

        .theme-switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: var(--gray);
            transition: 0.3s;
            border-radius: 28px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 22px;
            width: 22px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: 0.3s;
            border-radius: 50%;
        }

        input:checked+.slider {
            background-color: var(--primary);
        }

        input:checked+.slider:before {
            transform: translateX(24px);
        }
    </style>

    <script>
        let currentUserData = {};

        async function loadProfile() {
            const loadingState = document.getElementById('loadingState');
            const notAuthContent = document.getElementById('notAuthContent');
            const authenticatedContent = document.getElementById('authenticatedContent');

            const user = getUser();
            if (!user.id || !user.token) {
                loadingState.style.display = 'none';
                notAuthContent.style.display = 'block';
                return;
            }

            loadingState.style.display = 'none';
            authenticatedContent.style.display = 'block';

            const isDark = document.documentElement.classList.contains('dark');
            document.getElementById('themeToggle').checked = isDark;
            document.getElementById('themeLabel').textContent = isDark ? 'Mode sombre' : 'Mode clair';
            document.getElementById('themeIcon').className = isDark ? 'bx bxs-sun' : 'bx bxs-moon';

            document.getElementById('profileUsername').textContent = user.username;
            document.getElementById('infoUsername').textContent = user.username;

            let roleText = '';
            let roleStyle = '';
            switch (parseInt(user.role)) {
                case 1:
                    roleText = 'Super Administrateur';
                    roleStyle = 'background: #667eea; color: white;';
                    break;
                case 2:
                    roleText = 'Administrateur';
                    roleStyle = 'background: #10b981; color: white;';
                    break;
                case 3:
                    roleText = 'Lecteur';
                    roleStyle = 'background: #6b7280; color: white;';
                    break;
            }
            document.getElementById('profileRole').textContent = roleText;
            document.getElementById('profileRole').style.cssText = roleStyle;
            document.getElementById('infoRole').textContent = roleText;

            try {
                const userData = await apiRequest('/user');
                currentUserData = userData;
                document.getElementById('infoEmail').textContent = userData.email || 'Non disponible';
                localStorage.setItem('mobile_username', userData.username);

                const [products, categories, branches] = await Promise.all([
                    apiRequest('/products?status=1'),
                    apiRequest('/categories?status=1'),
                    apiRequest('/branches?status=1')
                ]);

                document.getElementById('statProducts').textContent = products.data?.length || products.total || 0;
                document.getElementById('statCategories').textContent = categories.data?.length || categories.total ||
                    0;
                document.getElementById('statBranches').textContent = branches.data?.length || branches.total || 0;
            } catch (error) {
                console.error('Error loading profile:', error);
                document.getElementById('infoEmail').textContent = 'Non disponible';
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadProfile();
        });
    </script>
</body>

</html>
