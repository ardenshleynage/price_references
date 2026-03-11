<div id="searchCategoryModal" class="modal-overlay" 
    data-block-url="{{ route('categories.block') }}"
    data-unblock-url="{{ route('categories.unblock') }}"
    data-delete-url="{{ route('categories.delete') }}"
    data-restore-url="{{ route('categories.restore') }}"
    data-erase-url="{{ route('categories.erase') }}"
    onclick="closeSearchCategoryModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeSearchCategoryModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>
            <h2 class="login-header">Détails catégorie</h2>
            <div class="login-container">
                <p><strong>Nom de la catégorie :</strong> <span id="searchModalCategoryItemName"></span></p>
                <p><strong>Crée le :</strong> <span id="searchModalCategoryItemCreatedAt"></span></p>
                <p><strong>Modifié le :</strong> <span id="searchModalCategoryItemUpdatedAt"></span></p>
                <p><strong>Status :</strong> <span id="searchModalCategoryItemStatus"></span></p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                <div id="searchModalCategoryActions"></div>
            </div>
        </div>
    </div>
