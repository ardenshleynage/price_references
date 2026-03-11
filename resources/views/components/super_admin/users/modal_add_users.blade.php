<div id="modalOverlay" class="modal-overlay" onclick="closeModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Nouveau utilisateur/Admin</h2>
        <form class="login-container" method="POST" action="{{ route('users.create_user') }}">
            @csrf
            <p><input type="text" name="username" placeholder="Nom d'utilisateur" required
                    value="{{ old('username') }}"></p>
            <p><input type="email" name="email" placeholder="Adresse email" required
                    value="{{ old('email') }}"></p>
            <p><input type="password" name="password" placeholder="Mots de passe" required></p>
            <p>
                <select name="role" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">Choisir le rôle</option>
                    @if (!$superAdminExists)
                        <option value="1" {{ old('role') == 1 ? 'selected' : '' }}>Super Admin</option>
                    @endif
                    <option value="2" {{ old('role') == 2 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ old('role') == 3 ? 'selected' : '' }}>Utilisateur</option>
                </select>
            </p>
            <p><input type="submit" value="Ajouter"></p>
        </form>
    </div>
</div>
{{ $slot }}
