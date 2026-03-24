    <div class="modal-overlay" id="editProductModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeEditProductModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier le produit</h2>
            </div>

            <form id="editProductForm" class="modal-body-mobile">
                <input type="hidden" id="editProductId" value="">

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom du produit *</label>
                    <input type="text" id="editProductName" class="form-input-mobile"
                        placeholder="Entrez le nom du produit" required>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Prix unitaire (HTG) *</label>
                    <input type="number" step="0.01" id="editSinglePrice" class="form-input-mobile"
                        placeholder="Prix unitaire" required>
                </div>

                <div class="form-group-mobile">
                    <button type="button" class="btn btn-toggle" id="toggleEditDetailedPriceBtn"
                        onclick="toggleEditDetailedPrice()">
                        + Ajouter un prix détaillé
                    </button>
                </div>

                <div class="form-group-mobile" id="editDetailedPriceContainer" style="display: none;">
                    <label class="form-label-mobile">Prix détaillé</label>
                    <input type="text" id="editDetailedPrice" class="form-input-mobile"
                        placeholder="Ex: 10 unités = 90 HTG">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Informations complémentaires</label>
                    <textarea id="editPostScriptum" class="form-textarea-mobile" placeholder="Informations complémentaires (optionnel)"
                        rows="3"></textarea>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Branche *</label>
                    <div class="custom-select" id="editBranchSelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('editBranchSelect')">
                            <span class="custom-select-value" id="editBranchValue">- Sélectionner une branche -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="editBranchOptions"></div>
                    </div>
                    <input type="hidden" id="editBranchId" value="">
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Catégorie *</label>
                    <div class="custom-select" id="editCategorySelect">
                        <div class="custom-select-trigger" onclick="toggleCustomSelect('editCategorySelect')">
                            <span class="custom-select-value" id="editCategoryValue">- Sélectionner une catégorie
                                -</span>
                            <i class='bx bxs-chevron-down'></i>
                        </div>
                        <div class="custom-select-options" id="editCategoryOptions"></div>
                    </div>
                    <input type="hidden" id="editCategoryId" value="">
                </div>

                <div class="form-error" id="editProductError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="editProductSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
