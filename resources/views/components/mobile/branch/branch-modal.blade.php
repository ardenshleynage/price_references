    <div class="modal-overlay" id="branchModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeBranchModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails branche</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="modalBranchName"></div>
                <div class="modal-status-badge" id="modalBranchStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="modalBranchCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="modalBranchUpdatedAt">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="modalBranchActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}
