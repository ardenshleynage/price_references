    <div class="modal-overlay" id="addBranchModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeAddBranchModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Nouvelle branche</h2>
            </div>

            <form id="addBranchForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nom de la branche *</label>
                    <input type="text" id="addBranchName" class="form-input-mobile"
                        placeholder="Entrez le nom de la branche" required>
                </div>

                <div class="form-error" id="addBranchError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="addBranchSubmitBtn">
                    <span class="btn-text">Ajouter</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}