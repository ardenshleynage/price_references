<div id="editProductModal" class="modal-overlay" style="display: none;">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeEditModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier le produit</h2>
        <form id="editProductForm" class="login-container" method="POST" action="{{ route('products.update') }}">
            @csrf
            <input type="hidden" id="editProductId" name="prod_id" value="">
            <!-- Nom du produit -->
            <p>
                <label for="product_name">Nom du produit :</label>
                <input type="text" id="editProductName" name="product_name" placeholder="Nom du produit" required>
            </p>
            <!-- Prix unitaire -->
            <p>
                <label for="single_price">Prix unitaire</label>
                <input type="number" step="0.01" id="editSinglePrice" name="single_price"
                    placeholder="Prix unitaire (HTG)" required>
            </p>
            <!-- Bouton pour afficher/masquer le prix détaillé -->
            <!-- Prix détaillé (caché par défaut) -->

            <p>
                <input type="text" id="editDetailedPrice" name="detailed_price"
                    placeholder="Prix détaillé (ex: 10 unités = 90 HTG)">
            </p>
            <!-- Post-scriptum -->
            <textarea id="editPostScriptum" name="post_scriptum" placeholder="Informations complémentaires (optionnel)"
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555; resize: none; overflow-y: auto; min-height: 80px; max-height: 200px;"></textarea>
            <!-- Branche -->
            <p>
                <select id="editBranchId" name="branch_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une branche --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}">{{ $branch->branche_name }}</option>
                    @endforeach
                </select>
            </p>
            <!-- Catégorie -->
            <p>
                <select id="editCategoryId" name="category_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                    @endforeach
                </select>
            </p>
            <p>
                <input type="submit" value="Enregistrer les modifications">
            </p>
        </form>
    </div>
</div>
{{ $slot }}
