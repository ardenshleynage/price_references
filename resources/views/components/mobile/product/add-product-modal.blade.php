    <div class="modal-overlay" id="addProductModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeAddProductModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Nouveau produit</h2>
            </div>

            <form id="addProductForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom du produit *</label>
                    <input type="text" id="addProductName" class="form-input-mobile"
                        placeholder="Entrez le nom du produit" required>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Prix unitaire (HTG) *</label>
                    <input type="number" step="0.01" id="addSinglePrice" class="form-input-mobile"
                        placeholder="Prix unitaire" required>
                </div>

                <div class="form-group-mobile">
                    <button type="button" class="btn btn-toggle" id="toggleDetailedPriceBtn"
                        onclick="toggleDetailedPriceMobile()">
                        + Ajouter un prix détaillé
                    </button>
                </div>

                <div class="form-group-mobile" id="detailedPriceContainerMobile" style="display: none;">
                    <label class="form-label-mobile">Prix détaillé</label>
                    <input type="text" id="addDetailedPrice" class="form-input-mobile"
                        placeholder="Ex: 10 unités = 90 HTG">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Informations complémentaires</label>
                    <textarea id="addPostScriptum" class="form-textarea-mobile" placeholder="Informations complémentaires (optionnel)"
                        rows="3"></textarea>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Branche *</label>
                    <div class="custom-select" id="branchSelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('branchSelect')">
                            <span class="custom-select-value">- Sélectionner une branche -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="branchOptions"></div>
                    </div>
                    <input type="hidden" id="addBranchId" value="">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Catégorie *</label>
                    <div class="custom-select" id="categorySelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('categorySelect')">
                            <span class="custom-select-value">- Sélectionner une catégorie -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="categoryOptions"></div>
                    </div>
                    <input type="hidden" id="addCategoryId" value="">
                </div>

                <div class="form-error" id="addProductError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="addProductSubmitBtn">
                    <span class="btn-text">Ajouter</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
