    <div class="modal-overlay" id="productModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeProductModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails produit</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="modalProductName"></div>
                <div class="modal-status-badge" id="modalStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Prix unitaire:</span>
                        <span class="modal-value" id="modalSinglePrice">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Prix détaillé:</span>
                        <span class="modal-value" id="modalDetailedPrice">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Catégorie:</span>
                        <span class="modal-value" id="modalCategoryName">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Branche:</span>
                        <span class="modal-value" id="modalBranchName">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="modalCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="modalUpdatedAt">-</span>
                    </div>
                    <div class="modal-row" id="postScriptumRow" style="display: none;">
                        <span class="modal-label">Infos complémentaires:</span>
                        <span class="modal-value" id="modalPostScriptum">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="modalActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}
