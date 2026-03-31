<div class="container page-dashboard" id="mainContainer">
    <!-- Loading state -->
    <div class="loading" id="loadingState">
        <div class="spinner"></div>
    </div>

    <!-- Authenticated content (hidden by default) -->
    <div id="authenticatedContent" style="display: none;">
        <!-- Welcome Section -->
        <div class="welcome-section">
            <h2>Bienvenue</h2>
            <p style="font-size: 14px; opacity: 0.9; margin-bottom: 12px;" id="welcomeUsername">-</p>
            <span class="role-badge" id="welcomeRole">-</span>
        </div>

        <!-- Stats Grid -->
        <div class="stats-grid" id="statsGrid">
            <!-- Users (Super Admin only - shown via JS) -->
            <a href="{{ route('mobile.users') }}" class="stat-card stat-users"
                style="text-decoration: none; display: none;" id="usersStatCard">
                <div class="icon" style="background: #fce7f3; color: #db2777;">
                    <i class='bx bxs-group'></i>
                </div>
                <div class="number" id="usersCount">-</div>
                <div class="label">Utilisateurs</div>
            </a>

            <a href="{{ route('mobile.products') }}" class="stat-card" style="text-decoration: none;">
                <div class="icon" style="background: #dbeafe; color: #2563eb;">
                    <i class='bx bxs-package'></i>
                </div>
                <div class="number" id="productsCount">-</div>
                <div class="label">Produits</div>
            </a>

            <a href="{{ route('mobile.categories') }}" class="stat-card" style="text-decoration: none;">
                <div class="icon" style="background: #d1fae5; color: #059669;">
                    <i class='bx bxs-folder'></i>
                </div>
                <div class="number" id="categoriesCount">-</div>
                <div class="label">Catégories</div>
            </a>

            <a href="{{ route('mobile.branches') }}" class="stat-card" style="text-decoration: none;">
                <div class="icon" style="background: #fef3c7; color: #d97706;">
                    <i class='bx bxs-store'></i>
                </div>
                <div class="number" id="branchesCount">-</div>
                <div class="label">Branches</div>
            </a>
        </div>

        <!-- Quick Actions -->

        <!-- Info Card -->
        <div class="card"
            style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">
            <h3 style="font-size: 16px; margin-bottom: 8px;">
                <i class='bx bxs-info-circle'></i>
                À propos
            </h3>
            <p style="font-size: 13px; opacity: 0.9; line-height: 1.5;">
                Bienvenue sur l'application mobile Price References. Gérez vos produits, catégories et branches en toute
                simplicité.
            </p>
            <p style="font-size: 12px; margin-top: 12px; opacity: 0.7;">
                Version 1.0.0
            </p>
        </div>
    </div>

    <!-- Not authenticated state -->
    <div class="empty-state" id="notAuthContent" style="display: none;">
        <i class='bx bxs-user-circle'></i>
        <h3>Session expirée</h3>
        <p>Veuillez vous reconnecter</p>
        <a href="{{ route('mobile.login') }}" class="btn btn-primary" style="margin-top: 20px; max-width: 200px;">
            Se connecter
        </a>
    </div>
</div>
