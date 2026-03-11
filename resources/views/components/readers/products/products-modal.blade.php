<div id="productModal" class="modal-overlay" onclick="closeProductModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeProductModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails produit</h2>
        <div class="login-container">
            <p><strong>Nom du produit :</strong> <span id="modalProductName"></span></p>
            <p><strong>Informations complémentaires :</strong> <span id="modalPostScriptum"></span></p>
            <p><strong>Prix unitaire (HTG) :</strong> <span id="modalSinglePrice"></span></p>
            <p><strong>Prix détaillé :</strong> <span id="modalDetailedPrice"></span></p>
            <p><strong>Branche :</strong> <span id="modalBranchName"></span></p>
            <p><strong>Catégorie :</strong> <span id="modalCategoryName"></span></p>
            <span id="modalBranchId" style="display: none;"></span>
            <span id="modalCategoryId" style="display: none;"></span>
        </div>
    </div>
</div>

{{ $slot }}
