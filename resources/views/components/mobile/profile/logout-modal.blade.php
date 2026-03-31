    <div class="modal-overlay" id="logoutModal">
        <div class="logout-modal-content">
            <button class="modal-close" onclick="closeLogoutModal()" aria-label="Fermer">
                <i class='bx bx-x'></i>
            </button>

            <div class="logout-header">
                <i class='bx bxs-log-out-circle'></i>
                <h2>Déconnexion</h2>
            </div>

            <div class="logout-body">
                <p>Êtes-vous sûr de vouloir vous déconnecter ?</p>
                
                <div class="logout-buttons">
                    <button type="button" class="btn btn-secondary" onclick="closeLogoutModal()">Annuler</button>
                    <button type="button" class="btn btn-danger" onclick="confirmLogout()">Oui, me déconnecter</button>
                </div>
            </div>
        </div>
    </div>
    {{ $slot }}