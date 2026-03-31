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
                    <div class="profile-value-with-action">
                        <span class="profile-value" id="infoUsername">-</span>
                        <button class="edit-btn" onclick="openEditUsernameModal()">
                            <i class='bx bxs-edit'></i>
                        </button>
                    </div>
                </div>

                <div class="profile-row">
                    <div class="profile-label">
                        <i class='bx bxs-envelope'></i>
                        <span>E-mail</span>
                    </div>
                    <div class="profile-value-with-action">
                        <span class="profile-value" id="infoEmail">-</span>
                        <button class="edit-btn" onclick="openEditEmailModal()">
                            <i class='bx bxs-edit'></i>
                        </button>
                    </div>
                </div>
            </div>

            <div class="profile-card">
                <h3>Modifier le mot de passe</h3>
                <button class="btn btn-primary" onclick="openChangePasswordModal()" style="width: 100%;">
                    <i class='bx bxs-lock'></i> Changer le mot de passe
                </button>
            </div>

            <div class="profile-card">
                <h3>Thème</h3>
                <div class="theme-row">
                    <span>Mode Sombre</span>
                    <label class="theme-switch">
                        <input type="checkbox" id="themeToggle" onchange="toggleTheme()">
                        <span class="slider"></span>
                    </label>
                </div>
            </div>

            <button class="btn btn-danger" onclick="openLogoutModal()">
                <i class='bx bxs-log-out'></i>
                Se déconnecter
            </button>
        </div>
    </main>
    {{ $slot }}
