    <div class="modal-overlay" id="searchBranchModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchBranchModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails branche</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="searchModalBranchName"></div>
                <div class="modal-status-badge" id="searchModalBranchStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="searchModalBranchCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="searchModalBranchUpdatedAt">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="searchModalBranchActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}