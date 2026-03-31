    <div class="modal-overlay" id="editUsernameModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeEditUsernameModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier le nom d'utilisateur</h2>
            </div>

            <form id="editUsernameForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nouveau nom d'utilisateur</label>
                    <input type="text" id="editUsernameInput" class="form-input-mobile" placeholder="Entrez le nouveau nom d'utilisateur" required>
                </div>

                <div class="form-error" id="editUsernameError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="editUsernameSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
