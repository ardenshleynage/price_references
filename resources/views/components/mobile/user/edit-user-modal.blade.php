<div class="modal-overlay" id="editUserModal">
    <div class="modal-content-mobile">
        <button class="modal-close" onclick="closeEditUserModal()">
            <i class='bx bx-x'></i>
        </button>

        <div class="modal-header-mobile">
            <h2>Modifier l'utilisateur</h2>
        </div>

        <form id="editUserForm" class="modal-body-mobile">
            <input type="hidden" id="editUserId" name="user_id">

            <div class="form-group-mobile">
                <label class="form-label-mobile">Nom d'utilisateur</label>
                <input type="text" id="editUsername" class="form-input-mobile" placeholder="Entrez le nom d'utilisateur">
            </div>

            <div class="form-group-mobile">
                <label class="form-label-mobile">E-mail</label>
                <input type="email" id="editEmail" class="form-input-mobile" placeholder="exemple@gmail.com">
            </div>

            <div class="form-group-mobile">
                <label class="form-label-mobile">Rôle</label>
                <div class="custom-select" id="editRoleSelect">
                    <input type="hidden" id="editRole" name="role" value="">
                    <div class="custom-select-trigger" onclick="toggleCustomSelect('editRoleSelect')">
                        <span class="custom-select-value" id="editRoleValue">- Sélectionner un rôle -</span>
                        <i class='bx bx-chevron-down'></i>
                    </div>
                    <div class="custom-select-options">
                        <div class="custom-select-option" onclick="selectEditRole('2', 'Administrateur')">Administrateur</div>
                        <div class="custom-select-option" onclick="selectEditRole('3', 'Lecteur')">Lecteur</div>
                    </div>
                </div>
            </div>

            <div class="form-error" id="editUserError" style="display: none;"></div>

            <button type="submit" class="btn btn-primary btn-full" id="editUserSubmitBtn">
                <span class="btn-text">Enregistrer</span>
                <span class="btn-spinner" style="display: none;"></span>
            </button>
        </form>
    </div>
</div>
{{ $slot }}
