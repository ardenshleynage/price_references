    <div class="modal-overlay" id="searchUserModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchUserModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails utilisateur</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="searchModalUserUsername"></div>
                <div class="modal-status-badge" id="searchModalUserStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">E-mail:</span>
                        <span class="modal-value" id="searchModalUserEmail">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Rôle:</span>
                        <span class="modal-value" id="searchModalUserRole">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Dernière connexion:</span>
                        <span class="modal-value" id="searchModalUserLastConnection">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="searchModalUserCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="searchModalUserUpdatedAt">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="searchModalUserActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}
