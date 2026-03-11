<div id="searchBranchModal" class="modal-overlay" 
    data-block-url="{{ route('admins.branches.delete') }}"
    data-unblock-url=""
    data-delete-url=""
    data-restore-url="{{ route('admins.branches.restore') }}"
    data-erase-url="{{ route('admins.branches.erase') }}"
    onclick="closeSearchBranchModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeSearchBranchModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>
            <h2 class="login-header">Détails branche</h2>
            <div class="login-container">
                <p><strong>Nom de la branche :</strong> <span id="searchModalBranchItemName"></span></p>
                <p><strong>Crée le :</strong> <span id="searchModalBranchItemCreatedAt"></span></p>
                <p><strong>Modifié le :</strong> <span id="searchModalBranchItemUpdatedAt"></span></p>
                <p><strong>Status :</strong> <span id="searchModalBranchItemStatus"></span></p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                <div id="searchModalBranchActions"></div>
            </div>
        </div>
    </div>
