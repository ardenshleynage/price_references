<div class="modal-overlay active" wire:click.self="closeEditCategoryModal">
    <div class="login modal-content" style="position: relative; margin: 0; width: 450px; max-width: 90%;">
        <button class="modal-close" wire:click="closeEditCategoryModal" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la catégorie</h2>
        <livewire:categories.form :category-id="$selectedCategory->id" :key="'edit-cat-' . $selectedCategory->id" />
    </div>
</div>
