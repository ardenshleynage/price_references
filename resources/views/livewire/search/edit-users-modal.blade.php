<div class="modal-overlay active" wire:click.self="closeEditUserModal">
    <div class="login modal-content" style="position: relative; margin: 0; width: 450px; max-width: 90%;">
        <button class="modal-close" wire:click="closeEditUserModal" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier l'utilisateur</h2>
        <livewire:users.form :user-id="$selectedUser->id" :key="'edit-user-' . $selectedUser->id" />
    </div>
</div>
