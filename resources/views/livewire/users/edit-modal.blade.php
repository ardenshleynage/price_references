            <div class="modal-overlay active" wire:click.self="$parent.$set('showEditModal', false)">
                <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
                    <button class="modal-close" wire:click="$parent.$set('showEditModal', false)"
                        aria-label="Fermer">&times;</button>
                    <div class="login-triangle"></div>
                    <h2 class="login-header">Modifier l'utilisateur</h2>
                    <livewire:users.form :user-id="$selectedUser->id" :key="'edit-' . $selectedUser->id" />
                </div>
            </div>
