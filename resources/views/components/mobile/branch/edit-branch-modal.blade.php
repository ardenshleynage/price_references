    <div class="modal-overlay" id="editBranchModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeEditBranchModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier la branche</h2>
            </div>

            <form id="editBranchForm" class="modal-body-mobile">
                <input type="hidden" id="editBranchId" value="">

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom de la branche *</label>
                    <input type="text" id="editBranchName" class="form-input-mobile"
                        placeholder="Entrez le nom de la branche" required>
                </div>

                <div class="form-error" id="editBranchError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="editBranchSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}