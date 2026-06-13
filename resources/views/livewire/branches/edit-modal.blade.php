<div class="modal-overlay active" wire:click.self="$parent.$set('showEditModal', false)">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" wire:click="$parent.$set('showEditModal', false)" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la branche</h2>
        <livewire:branches.form :branch-id="$selectedBranch->id" :key="'edit-' . $selectedBranch->id" />
    </div>
</div>
