<div>
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    <div class="profile-container">
        <div class="profile-card">
            <h3><i class='bx bxs-user'></i> Nom d'utilisateur</h3>
            <form wire:submit="updateUsername">
                <div class="form-group">
                    <input wire:model="username" type="text" placeholder="Nouveau nom d'utilisateur">
                    @error('username') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>

        <div class="profile-card">
            <h3><i class='bx bxs-envelope'></i> Adresse email</h3>
            <form wire:submit="updateEmail">
                <div class="form-group">
                    <input wire:model="email" type="email" placeholder="Nouvelle adresse email (@gmail.com)">
                    @error('email') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </form>
        </div>

        <div class="profile-card">
            <h3><i class='bx bxs-lock-alt'></i> Mot de passe</h3>
            <form wire:submit="updatePassword">
                <div class="form-group">
                    <input wire:model="current_password" type="password" placeholder="Mot de passe actuel">
                    @error('current_password') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input wire:model="new_password" type="password" placeholder="Nouveau mot de passe">
                    @error('new_password') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <div class="form-group">
                    <input wire:model="confirm_password" type="password" placeholder="Confirmer le mot de passe">
                    @error('confirm_password') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary">Changer le mot de passe</button>
            </form>
        </div>

        <div class="profile-card">
            <h3><i class='bx bxs-palette'></i> Thème</h3>
            <form wire:submit="updateTheme">
                <div class="form-group">
                    <select wire:model="theme" id="theme-select">
                        <option value="light">Mode clair</option>
                        <option value="dark">Mode sombre</option>
                    </select>
                    @error('theme') <span class="error-message">{{ $message }}</span> @enderror
                </div>
                <button type="submit" class="btn-primary">Appliquer</button>
            </form>
        </div>
    </div>
</div>
