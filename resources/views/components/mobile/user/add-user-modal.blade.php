<div class="modal-overlay" id="addUserModal">
    <div class="modal-content-mobile">
        <button class="modal-close" onclick="closeAddUserModal()">
            <i class='bx bx-x'></i>
        </button>

        <div class="modal-header-mobile">
            <h2>Ajouter un utilisateur</h2>
        </div>

        <form id="addUserForm" class="modal-body-mobile">
            <div class="form-group-mobile">
                <label class="form-label-mobile">Nom d'utilisateur *</label>
                <input type="text" id="addUsername" class="form-input-mobile" placeholder="Entrez le nom d'utilisateur" required>
            </div>

            <div class="form-group-mobile">
                <label class="form-label-mobile">E-mail *</label>
                <input type="email" id="addEmail" class="form-input-mobile" placeholder="exemple@gmail.com" required>
            </div>

            <div class="form-group-mobile">
                <label class="form-label-mobile">Mot de passe *</label>
                <input type="password" id="addPassword" class="form-input-mobile" placeholder="Entrez le mot de passe" required minlength="4">
            </div>

            <div class="form-group-mobile">
                <label class="form-label-mobile">Rôle *</label>
                <div class="custom-select" id="roleSelect">
                    <input type="hidden" id="addRole" name="role" value="">
                    <div class="custom-select-trigger" onclick="toggleCustomSelect('roleSelect')">
                        <span class="custom-select-value">- Sélectionner un rôle -</span>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <div class="custom-select-options">
                        <div class="custom-select-option" onclick="selectOption('roleSelect', '2', 'Administrateur')">Administrateur</div>
                        <div class="custom-select-option" onclick="selectOption('roleSelect', '3', 'Lecteur')">Lecteur</div>
                    </div>
                </div>
            </div>

            <div class="form-error" id="addUserError" style="display: none;"></div>

            <button type="submit" class="btn btn-primary btn-full" id="addUserSubmitBtn">
                <span class="btn-text">Ajouter</span>
                <span class="btn-spinner" style="display: none;"></span>
            </button>
        </form>
    </div>
</div>
{{ $slot }}
