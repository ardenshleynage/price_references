            <div class="modal-overlay active" wire:click.self="$parent.$set('showEditModal', false)">
                <div class="login modal-content" style="position: relative; margin: 0; width: 450px; max-width: 90%;">
                    <button class="modal-close" wire:click="$parent.$set('showEditModal', false)"
                        aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Modifier le produit</h2>
                    <livewire:products.form :product-id="$selectedProduct->id" :key="'edit-' . $selectedProduct->id" :user-role="$userRole" />
                </div>
            </div>
