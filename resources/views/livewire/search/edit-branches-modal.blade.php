<div class="modal-overlay active" wire:click.self="closeEditBranchModal">
    <div class="login modal-content" style="position: relative; margin: 0; width: 450px; max-width: 90%;">
        <button class="modal-close" wire:click="closeEditBranchModal" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la branche</h2>
        <livewire:branches.form :branch-id="$selectedBranch->id" :key="'edit-branch-' . $selectedBranch->id" />
    </div>
</div>
