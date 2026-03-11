<div id="searchCategoryEditModal" class="modal-overlay" style="display: none;">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" onclick="closeSearchCategoryEditModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la catégorie</h2>
        <form id="searchEditCategoryForm" class="login-container" action="{{ route('admins.categories.update') }}"
            method="POST">
            @csrf
            <input type="hidden" id="searchEditCategoryId" name="category_id" value="">
            <input type="hidden" id="searchEditCategoryQ" name="q" value="">
            <p>
                <input type="text" id="searchEditCategoryName" name="category_name" required
                    placeholder="Nom de la catégorie">
            </p>
            <p>
                <button type="submit" class="action-btn edit-submit-btn">Enregistrer les modifications</button>
            </p>
        </form>
    </div>
</div>
