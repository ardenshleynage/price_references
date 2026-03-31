    <div class="modal-overlay" id="confirmEraseCategoryModal">
        <div class="modal-content-mobile" style="max-width: 320px;">
            <div class="modal-header-mobile" style="background: var(--danger);">
                <h2>Confirmation</h2>
            </div>
            <div class="modal-body-mobile">
                <p style="text-align: center; margin-bottom: 20px;">
                    Êtes-vous sûr de vouloir supprimer définitivement cette catégorie ?
                </p>
                <div class="confirm-buttons">
                    <button class="btn btn-secondary" onclick="closeConfirmEraseCategoryModal()">Non</button>
                    <button class="btn btn-danger" id="confirmEraseCategoryBtn" onclick="confirmEraseCategoryFromSearch()">Oui</button>
                </div>
            </div>
        </div>
    </div>
    {{ $slot }}