    <div class="modal-overlay" id="editEmailModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeEditEmailModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Modifier l'adresse e-mail</h2>
            </div>

            <form id="editEmailForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nouvelle adresse e-mail</label>
                    <input type="email" id="editEmailInput" class="form-input-mobile" placeholder="exemple@gmail.com" required>
                </div>

                <div class="form-error" id="editEmailError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="editEmailSubmitBtn">
                    <span class="btn-text">Enregistrer</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
