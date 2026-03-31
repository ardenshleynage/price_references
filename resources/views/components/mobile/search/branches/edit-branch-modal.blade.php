    <div class="modal-overlay" id="searchEditBranchModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeSearchEditBranchModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier la branche</h2>
            </div>

            <form id="searchEditBranchForm" class="modal-body-mobile">
                <input type="hidden" id="searchEditBranchId" value="">

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom de la branche *</label>
                    <input type="text" id="searchEditBranchName" class="form-input-mobile"
                        placeholder="Entrez le nom de la branche" required>
                </div>

                <div class="form-error" id="searchEditBranchError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="searchEditBranchSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}