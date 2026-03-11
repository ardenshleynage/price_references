<div id="productAddModal" class="modal-overlay" onclick="closeProductModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeProductModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Nouveau produit</h2>

        <form id="createProductForm" class="login-container" method="POST" action="{{ route('products.create') }}">
            @csrf

            <!-- Nom du produit -->
            <p>
                <input type="text" name="product_name" placeholder="Nom du produit" required
                    value="{{ old('product_name') }}">
            </p>

            <!-- Prix unitaire -->
            <p>
                <input type="number" step="0.01" name="single_price" placeholder="Prix unitaire (HTG)" required
                    value="{{ old('single_price') }}">
            </p>

            <!-- Bouton pour afficher/masquer le prix détaillé -->
            <p style="padding: 0 12px;">
                <button type="button" class="action-btn" onclick="toggleDetailedPrice()"
                    style="background: #28d; color: white; border: none; padding: 12px; width: 100%; cursor: pointer; border-radius: 4px; font-size: 0.95em;">
                    + Ajouter un prix détaillé
                </button>
            </p>

            <!-- Prix détaillé (caché par défaut) -->
            <div id="detailedPriceContainer" style="display: none;">
                <p>
                    <input type="text" name="detailed_price" placeholder="Prix détaillé (ex: 10 unités = 90 HTG)"
                        value="{{ old('detailed_price') }}">
                </p>
            </div>

            <!-- Post-scriptum -->
            <!-- APRÈS -->
            <textarea name="post_scriptum" placeholder="Informations complémentaires (optionnel)"
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555; resize: none; overflow-y: auto; min-height: 80px; max-height: 200px;">{{ old('post_scriptum') }}</textarea>


            <!-- Sélection de la branche -->
            <p>
                <select name="branch_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une branche --</option>
                    @foreach ($branches as $branch)
                        <option value="{{ $branch->id }}" {{ old('branch_id') == $branch->id ? 'selected' : '' }}>
                            {{ $branch->branche_name }}
                        </option>
                    @endforeach
                </select>
            </p>

            <!-- Sélection de la catégorie -->
            <p>
                <select name="category_id" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">-- Sélectionner une catégorie --</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->category_name }}
                        </option>
                    @endforeach
                </select>
            </p>

            <!-- Bouton de soumission -->
            <p>
                <input type="submit" value="Ajouter">
            </p>
        </form>
    </div>
</div>
{{ $slot }}
