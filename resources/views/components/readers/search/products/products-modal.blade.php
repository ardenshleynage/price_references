<div id="searchProductModal" class="modal-overlay" onclick="closeSearchProductModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeSearchProductModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails produit</h2>
        <div class="login-container">
            <p><strong>Nom du produit :</strong> <span id="searchModalProductName"></span></p>
            <p><strong>Informations complémentaires :</strong> <span id="searchModalPostScriptum"></span></p>
            <p><strong>Prix unitaire ($HT) :</strong> <span id="searchModalSinglePrice"></span></p>
            <p><strong>Prix détaillé :</strong> <span id="searchModalDetailedPrice"></span></p>
            <p><strong>Branche :</strong> <span id="searchModalBranchName"></span></p>
            <p><strong>Catégorie :</strong> <span id="searchModalCategoryName"></span></p>
            <span id="searchModalBranchId" style="display: none;"></span>
            <span id="searchModalCategoryId" style="display: none;"></span>
        </div>
    </div>
</div>
