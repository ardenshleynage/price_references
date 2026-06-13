<div class="modal-overlay active" wire:click="closeProductModal">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" type="button" wire:click="closeProductModal">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails produit</h2>
        <div class="login-container">
            <p><strong>Nom :</strong> {{ $selectedProduct['product_name'] }}</p>
            <p><strong>Prix unitaire :</strong> {{ $selectedProduct['single_price'] }} $HT</p>
            @if (!empty($selectedProduct['detailed_price']))
                <p><strong>Prix détaillé :</strong> {{ $selectedProduct['detailed_price'] }}</p>
            @endif
            @if (!empty($selectedProduct['post_scriptum']))
                <p><strong>Description :</strong> {{ $selectedProduct['post_scriptum'] }}</p>
            @endif
            <p><strong>Branche :</strong> {{ $selectedProduct['branch_name'] }}</p>
            <p><strong>Catégorie :</strong> {{ $selectedProduct['category_name'] }}</p>
            <p><strong>Crée le :</strong> {{ $selectedProduct['created_at_formatted'] }}</p>
            <p><strong>Status :</strong>
                @switch($selectedProduct['status'])
                    @case(1)
                        <span class="status completed">Actif</span>
                    @break

                    @case(2)
                        <span class="status pending">Bloqué</span>
                    @break

                    @case(0)
                        <span class="status process">Supprimé</span>
                    @break
                @endswitch
            </p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            @if ($userRole <= 2)
                <div style="display: flex; flex-wrap: wrap; gap: 8px;">
                    @if ($selectedProduct['status'] === 1)
                        @if ($userRole <= 2)
                            <button class="action-btn"
                                style="background: #e67e22; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="blockProduct({{ $selectedProduct['id'] }})">
                                <i class='bx bx-lock'></i> Bloquer
                            </button>
                        @endif
                        <button class="action-btn"
                            style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="deleteProduct({{ $selectedProduct['id'] }})">
                            <i class='bx bx-trash'></i> Supprimer
                        </button>
                    @elseif ($selectedProduct['status'] === 2)
                        @if ($userRole <= 2)
                            <button class="action-btn"
                                style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="unblockProduct({{ $selectedProduct['id'] }})">
                                <i class='bx bx-lock-open'></i> Débloquer
                            </button>
                            <button class="action-btn"
                                style="background: #e74c3c; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="deleteProduct({{ $selectedProduct['id'] }})">
                                <i class='bx bx-trash'></i> Supprimer
                            </button>
                            <button class="action-btn"
                                style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                                wire:click="confirmEraseProduct({{ $selectedProduct['id'] }})">
                                <i class='bx bx-x-circle'></i> Supprimer définitivement
                            </button>
                        @endif
                    @elseif ($selectedProduct['status'] === 0)
                        <button class="action-btn"
                            style="background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="restoreProduct({{ $selectedProduct['id'] }})">
                            <i class='bx bx-revision'></i> Restaurer
                        </button>
                        <button class="action-btn"
                            style="background: #c0392b; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                            wire:click="confirmEraseProduct({{ $selectedProduct['id'] }})">
                            <i class='bx bx-x-circle'></i> Supprimer définitivement
                        </button>
                    @endif
                    <button class="action-btn"
                        style="background: #3498db; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer;"
                        wire:click="openEditProductModal({{ $selectedProduct['id'] }})">
                        <i class='bx bx-edit'></i> Modifier
                    </button>
                </div>
            @endif
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            <p style="text-align: center;">
                <a href="{{ route($productRoute) }}"
                    style="display: inline-block; padding: 10px 20px; background: #28d; color: #fff; text-decoration: none; border-radius: 4px; font-size: 14px;">Voir
                    tous les produits</a>
            </p>
        </div>
    </div>
</div>
