<div class="modal-overlay" id="userModal">
    <div class="modal-content-mobile">
        <button class="modal-close" onclick="closeUserModal()">
            <i class='bx bx-x'></i>
        </button>
        <div class="modal-header-mobile">
            <h2>Détails de l'utilisateur</h2>
        </div>
        <div class="modal-body-mobile">
            <div class="modal-product-name" id="modalUsername"></div>
            <div class="modal-status-badge" id="modalStatusBadge"></div>

            <div class="modal-details">
                <div class="modal-row">
                    <span class="modal-label">E-mail:</span>
                    <span class="modal-value" id="modalEmail">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Rôle:</span>
                    <span class="modal-value" id="modalRole">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Dernière connexion:</span>
                    <span class="modal-value" id="modalLastConnection">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Créé le:</span>
                    <span class="modal-value" id="modalCreatedAt">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Modifié le:</span>
                    <span class="modal-value" id="modalUpdatedAt">-</span>
                </div>
            </div>

            <div class="modal-actions" id="modalActions">
            </div>
        </div>
    </div>
</div>
{{ $slot }}
