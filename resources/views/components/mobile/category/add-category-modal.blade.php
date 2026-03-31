    <div class="modal-overlay" id="addCategoryModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeAddCategoryModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Nouvelle catégorie</h2>
            </div>

            <form id="addCategoryForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom de la catégorie *</label>
                    <input type="text" id="addCategoryName" class="form-input-mobile"
                        placeholder="Entrez le nom de la catégorie" required>
                </div>

                <div class="form-error" id="addCategoryError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="addCategorySubmitBtn">
                    <span class="btn-text">Ajouter</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}