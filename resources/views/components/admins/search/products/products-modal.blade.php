<div id="searchProductModal" class="modal-overlay" 
    data-block-url="{{ route('admins.products.delete') }}"
    data-unblock-url=""
    data-delete-url=""
    data-restore-url="{{ route('admins.products.restore') }}"
    data-erase-url="{{ route('admins.products.erase') }}"
    onclick="closeSearchProductModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeSearchProductModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>
            <h2 class="login-header">Détails produit</h2>
            <div class="login-container">
                <p><strong>Nom du produit :</strong> <span id="searchModalProductName"></span></p>
                <p><strong>Prix unitaire (HTG) :</strong> <span id="searchModalSinglePrice"></span></p>
                <p><strong>Prix détaillé :</strong> <span id="searchModalDetailedPrice"></span></p>
                <p><strong>Description :</strong> <span id="searchModalPostScriptum"></span></p>
                <p><strong>Branche :</strong> <span id="searchModalBranchName"></span></p>
                <p><strong>Catégorie :</strong> <span id="searchModalCategoryName"></span></p>
                <p><strong>Crée le :</strong> <span id="searchModalCreatedAt"></span></p>
                <p><strong>Modifié le :</strong> <span id="searchModalUpdatedAt"></span></p>
                <p><strong>Status :</strong> <span id="searchModalStatus"></span></p>
                <span id="searchModalBranchId" style="display: none;"></span>
                <span id="searchModalCategoryId" style="display: none;"></span>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                <div id="searchModalProductActions"></div>
            </div>
        </div>
    </div>
