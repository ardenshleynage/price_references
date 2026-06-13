<div class="modal-overlay active" wire:click.self="$parent.$set('showAddModal', false)">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" wire:click="$parent.$set('showAddModal', false)" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Nouveau utilisateur/Admin</h2>
        <livewire:users.form :key="'create'" />
    </div>
</div>
