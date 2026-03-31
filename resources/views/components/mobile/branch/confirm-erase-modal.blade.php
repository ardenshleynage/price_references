    <div class="modal-overlay" id="confirmEraseBranchModal">
        <div class="modal-content-mobile" style="max-width: 320px;">
            <div class="modal-header-mobile" style="background: var(--danger);">
                <h2>Confirmation</h2>
            </div>
            <div class="modal-body-mobile">
                <p style="text-align: center; margin-bottom: 20px;">
                    Êtes-vous sûr de vouloir supprimer définitivement cette branche ?
                </p>
                <div class="confirm-buttons">
                    <button class="btn btn-secondary" onclick="closeConfirmEraseBranchModal()">Non</button>
                    <button class="btn btn-danger" id="confirmEraseBranchBtn" onclick="confirmEraseBranch()">Oui</button>
                </div>
            </div>
        </div>
    </div>
    {{ $slot }}