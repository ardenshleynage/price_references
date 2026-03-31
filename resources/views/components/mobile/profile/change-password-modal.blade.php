    <div class="modal-overlay" id="changePasswordModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeChangePasswordModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Changer le mot de passe</h2>
            </div>

            <form id="changePasswordForm" class="modal-body-mobile">
                <div class="form-group-mobile">
                    <label class="form-label-mobile">Mot de passe actuel</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="currentPassword" class="form-input-mobile" placeholder="Entrez le mot de passe actuel" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('currentPassword', 'toggleCurrentPassword')">
                            <i class='bx bx-show' id="toggleCurrentPassword"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Nouveau mot de passe</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="newPassword" class="form-input-mobile" placeholder="Entrez le nouveau mot de passe" required minlength="4">
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('newPassword', 'toggleNewPassword')">
                            <i class='bx bx-show' id="toggleNewPassword"></i>
                        </button>
                    </div>
                </div>

                <div class="form-group-mobile">
                    <label class="form-label-mobile">Confirmer le mot de passe</label>
                    <div class="password-input-wrapper">
                        <input type="password" id="confirmPassword" class="form-input-mobile" placeholder="Confirmez le mot de passe" required>
                        <button type="button" class="toggle-password" onclick="togglePasswordVisibility('confirmPassword', 'toggleConfirmPassword')">
                            <i class='bx bx-show' id="toggleConfirmPassword"></i>
                        </button>
                    </div>
                </div>

                <div class="form-error" id="changePasswordError" style="display: none;"></div>

                <button type="submit" class="btn btn-primary btn-full" id="changePasswordSubmitBtn">
                    <span class="btn-text">Changer le mot de passe</span>
                    <span class="btn-spinner" style="display: none;"></span>
                </button>
            </form>
        </div>
    </div>
    {{ $slot }}
