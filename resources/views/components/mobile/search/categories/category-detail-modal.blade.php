    <div class="modal-overlay" id="searchCategoryModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchCategoryModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails catégorie</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="searchModalCategoryName"></div>
                <div class="modal-status-badge" id="searchModalCategoryStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="searchModalCategoryCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="searchModalCategoryUpdatedAt">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="searchModalCategoryActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}