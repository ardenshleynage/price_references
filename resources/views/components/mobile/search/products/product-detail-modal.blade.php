    <div class="modal-overlay" id="searchProductModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchProductModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails produit</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="searchModalProductName"></div>
                <div class="modal-status-badge" id="searchModalStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Prix unitaire:</span>
                        <span class="modal-value" id="searchModalSinglePrice">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Prix détaillé:</span>
                        <span class="modal-value" id="searchModalDetailedPrice">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Catégorie:</span>
                        <span class="modal-value" id="searchModalProductCategoryName">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Branche:</span>
                        <span class="modal-value" id="searchModalProductBranchName">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="searchModalCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="searchModalUpdatedAt">-</span>
                    </div>
                    <div class="modal-row" id="searchPostScriptumRow" style="display: none;">
                        <span class="modal-label">Infos complémentaires:</span>
                        <span class="modal-value" id="searchModalPostScriptum">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="searchModalActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}
