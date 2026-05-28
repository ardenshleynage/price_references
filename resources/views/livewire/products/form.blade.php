<div>
    <form wire:submit="save" class="login-container">
        @csrf
        <p>
            <input wire:model="product_name" type="text" placeholder="Nom du produit" required>
            @error('product_name') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        </p>
        <p>
            <input wire:model="single_price" type="number" step="0.01" placeholder="Prix unitaire ($HT)" required>
            @error('single_price') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        </p>
        <p style="padding: 0 12px;">
            <button type="button" class="action-btn" wire:click.prevent="toggleDetailedPrice"
                style="background: #28d; color: white; border: none; padding: 12px; width: 100%; cursor: pointer; border-radius: 4px; font-size: 0.95em;">
                @if ($showDetailedPrice)
                    - Retirer le prix détaillé
                @else
                    + Ajouter un prix détaillé
                @endif
            </button>
        </p>
        @if ($showDetailedPrice)
            <p>
                <input wire:model="detailed_price" type="text" placeholder="Prix détaillé (ex: 10 unités = 90 $HT)">
                @error('detailed_price') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
            </p>
        @endif
        <textarea wire:model="post_scriptum" placeholder="Informations complémentaires (optionnel)"
            style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555; resize: none; overflow-y: auto; min-height: 80px; max-height: 200px;"></textarea>
        @error('post_scriptum') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        <p>
            <select wire:model="branch_id" required
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                <option value="">-- Sélectionner une branche --</option>
                @foreach ($branches as $branch)
                    <option value="{{ $branch->id }}">{{ $branch->branche_name }}</option>
                @endforeach
            </select>
            @error('branch_id') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        </p>
        <p>
            <select wire:model="category_id" required
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                <option value="">-- Sélectionner une catégorie --</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->category_name }}</option>
                @endforeach
            </select>
            @error('category_id') <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span> @enderror
        </p>
        <p><input type="submit" value="{{ $mode === 'create' ? 'Ajouter' : 'Enregistrer les modifications' }}"></p>
    </form>
</div>
