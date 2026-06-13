<div class="modal-overlay active" wire:click.self="$parent.$set('showEditModal', false)">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" wire:click="$parent.$set('showEditModal', false)" aria-label="Fermer">×</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la catégorie</h2>
        <livewire:categories.form :category-id="$selectedCategory->id" :key="'edit-' . $selectedCategory->id" />
    </div>
</div>
