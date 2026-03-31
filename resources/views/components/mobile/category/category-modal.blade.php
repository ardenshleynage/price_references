    <div class="modal-overlay" id="categoryModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeCategoryModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Détails catégorie</h2>
            </div>

            <div class="modal-body-mobile">
                <div class="modal-product-name" id="modalCategoryName"></div>
                <div class="modal-status-badge" id="modalCategoryStatusBadge"></div>

                <div class="modal-details">
                    <div class="modal-row">
                        <span class="modal-label">Créé le:</span>
                        <span class="modal-value" id="modalCategoryCreatedAt">-</span>
                    </div>
                    <div class="modal-row">
                        <span class="modal-label">Modifié le:</span>
                        <span class="modal-value" id="modalCategoryUpdatedAt">-</span>
                    </div>
                </div>

                <div class="modal-actions" id="modalCategoryActions"></div>
            </div>
        </div>
    </div>
    {{ $slot }}