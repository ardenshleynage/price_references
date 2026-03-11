<div id="categoryModal" class="modal-overlay" onclick="closeCategoryModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeCategoryModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails catégorie</h2>
        <div class="login-container">
            <p><strong>Nom de la catégorie :</strong> <span id="modalCategoryName"></span></p>
        </div>
    </div>
</div>
