<div id="editUsersModal" class="modal-overlay" style="display: none;">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" onclick="closeEditModal()" aria-label="Fermer"
            style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 28px; font-weight: bold; color: #fff; cursor: pointer;">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier l'utilisateur</h2>
        <div class="login-container">
            <form id="editUserForm" action="{{ route('users.update') }}" method="POST">
                @csrf
                <input type="hidden" id="editUserId" name="user_id" value="">
                <p>
                    <input type="text" id="editUsername" name="username" required placeholder="Nom d'utilisateur">
                </p>
                <p>
                    <select id="editUserRole" name="role" required
                        style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                        <option value="">-- Sélectionner un rôle --</option>
                        <option value="2">Admin</option>
                        <option value="3">Utilisateur</option>
                    </select>
                </p>
                <p>
                    <button type="submit" class="action-btn"
                        style="background: #28d; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; cursor: pointer;">
                        Enregistrer les modifications
                    </button>
                </p>
            </form>
        </div>
    </div>
</div>
