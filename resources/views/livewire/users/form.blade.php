<div>
    <form wire:submit="save" class="login-container">
        @csrf
        <p>
            <input wire:model="username" type="text" placeholder="Nom d'utilisateur" required>
            @error('username')
                <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span>
            @enderror
        </p>
        <p>
            <input wire:model="email" type="email" placeholder="Adresse email" required>
            @error('email')
                <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span>
            @enderror
        </p>
        @if ($mode === 'create')
            <p>
                <input wire:model="password" type="password" placeholder="Mot de passe" required>
                @error('password')
                    <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span>
                @enderror
            </p>
        @endif
        <p>
            <select wire:model="role" required
                style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                <option value="">Choisir le rôle</option>
                @if ($mode === 'create' && !$superAdminExists)
                    <option value="1">Super Admin</option>
                @endif
                @if ($mode === 'edit')
                    <option value="2">Admin</option>
                    <option value="3">Lecteur</option>
                @else
                    <option value="2">Admin</option>
                    <option value="3">Lecteur</option>
                @endif
            </select>
            @error('role')
                <span style="color: #e74c3c; font-size: 13px;">{{ $message }}</span>
            @enderror
        </p>
        <p><input type="submit" value="{{ $mode === 'create' ? 'Ajouter' : 'Enregistrer les modifications' }}"></p>
    </form>
</div>
