<div class="modal-overlay active" wire:click.self="$parent.$set('showAddModal', false)">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" wire:click="$parent.$set('showAddModal', false)" aria-label="Fermer">×</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Nouvelle catégorie</h2>
        <livewire:categories.form :key="'create'" />
    </div>
</div>
