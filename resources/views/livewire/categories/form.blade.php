<div>
    <form wire:submit="save" class="login-container">
        @csrf
        <p>
            <input wire:model="category_name" type="text" placeholder="Nom de la catégorie" required>
            @error('category_name') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        </p>
        <p><input type="submit" value="{{ $mode === 'create' ? 'Ajouter' : 'Enregistrer les modifications' }}"></p>
    </form>
</div>