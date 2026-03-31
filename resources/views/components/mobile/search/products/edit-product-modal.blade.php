    <div class="modal-overlay" id="searchEditProductModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchEditProductModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier le produit</h2>
            </div>

            <form id="searchEditProductForm" class="modal-body-mobile">
                <input type="hidden" id="searchEditProductId" value="">

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom du produit *</label>
                    <input type="text" id="searchEditProductName" class="form-input-mobile"
                        placeholder="Entrez le nom du produit" required>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Prix unitaire (HTG) *</label>
                    <input type="number" step="0.01" id="searchEditSinglePrice" class="form-input-mobile"
                        placeholder="Prix unitaire" required>
                </div>

                <div class="form-group-mobile">
                    <button type="button" class="btn btn-toggle" id="searchToggleEditDetailedPriceBtn"
                        onclick="toggleSearchEditDetailedPrice()">
                        + Ajouter un prix détaillé
                    </button>
                </div>

                <div class="form-group-mobile" id="searchEditDetailedPriceContainer" style="display: none;">
                    <label class="form-label-mobile">Prix détaillé</label>
                    <input type="text" id="searchEditDetailedPrice" class="form-input-mobile"
                        placeholder="Ex: 10 unités = 90 HTG">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Informations complémentaires</label>
                    <textarea id="searchEditPostScriptum" class="form-textarea-mobile" placeholder="Informations complémentaires (optionnel)"
                        rows="3"></textarea>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Branche *</label>
                    <div class="custom-select" id="searchEditBranchSelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('searchEditBranchSelect')">
                            <span class="custom-select-value" id="searchEditBranchValue">- Sélectionner une branche -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="searchEditBranchOptions"></div>
                    </div>
                    <input type="hidden" id="searchEditBranchId" value="">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Catégorie *</label>
                    <div class="custom-select" id="searchEditCategorySelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('searchEditCategorySelect')">
                            <span class="custom-select-value" id="searchEditCategoryValue">- Sélectionner une catégorie
                                -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="searchEditCategoryOptions"></div>
                    </div>
                    <input type="hidden" id="searchEditCategoryId" value="">
                </div>

                <div class="form-error" id="searchEditProductError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="searchEditProductSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
