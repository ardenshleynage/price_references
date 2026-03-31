    <div class="modal-overlay" id="searchEditCategoryModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchEditCategoryModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier la catégorie</h2>
            </div>

            <form id="searchEditCategoryForm" class="modal-body-mobile">
                <input type="hidden" id="searchEditCategoryId" value="">

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom de la catégorie *</label>
                    <input type="text" id="searchEditCategoryName" class="form-input-mobile"
                        placeholder="Entrez le nom de la catégorie" required>
                </div>

                <div class="form-error" id="searchEditCategoryError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="searchEditCategorySubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}