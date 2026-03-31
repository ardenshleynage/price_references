<!DOCTYPE html>
<html lang="fr">

<x-mobile.header-login />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">
    <div class="login-page">
        <div class="login-logo">
            <img src="{{ asset('images/bx--bxs-smile.png') }}" alt="Logo">
            <h1>Price References</h1>
            <p>Application Mobile</p>
        </div>

        <div class="login-card">
            <h2>Connexion</h2>

            <div class="error-message" id="errorMessage"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-input"
                        placeholder="Entrez votre nom d'utilisateur" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <div class="password-input-wrapper">
                        <input type="password" name="password" id="loginPassword" class="form-input" placeholder="Entrez votre mot de passe"
                            required>
                        <button type="button" class="password-toggle" onclick="toggleLoginPassword()">
                            <i class='bx bx-show'></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class='bx bxs-log-in'></i>
                    Se connecter
                </button>

                <button type="button" class="btn btn-link" onclick="openForgotPasswordModal()">
                    Mot de passe oublié ?
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>Version 1.0.0</p>
        </div>
    </div>

    <!-- script -->
    <x-mobile.footer />

    <script>
        function toggleLoginPassword() {
            const input = document.getElementById('loginPassword');
            const toggleBtn = document.querySelector('.password-toggle i');
            
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.className = 'bx bx-hide';
            } else {
                input.type = 'password';
                toggleBtn.className = 'bx bx-show';
            }
        }

        function openForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.add('active');
        }

        function closeForgotPasswordModal() {
            document.getElementById('forgotPasswordModal').classList.remove('active');
            document.getElementById('forgotPasswordError').style.display = 'none';
            document.getElementById('forgotPasswordSuccess').style.display = 'none';
            document.getElementById('forgotPasswordForm').reset();
        }

        function openResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.add('active');
        }

        function closeResetPasswordModal() {
            document.getElementById('resetPasswordModal').classList.remove('active');
            document.getElementById('resetPasswordError').style.display = 'none';
        }

        document.getElementById('forgotPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('forgotPasswordSubmitBtn');
            const errorEl = document.getElementById('forgotPasswordError');
            const successEl = document.getElementById('forgotPasswordSuccess');
            const email = document.getElementById('forgotPasswordEmail').value.trim();
            
            if (!email) return;
            
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-spinner').style.display = 'inline-block';
            errorEl.style.display = 'none';
            successEl.style.display = 'none';

            try {
                const response = await apiRequest('/password/forgot', {
                    method: 'POST',
                    body: JSON.stringify({ email })
                });
                successEl.textContent = response.message;
                successEl.style.display = 'block';
                setTimeout(() => closeForgotPasswordModal(), 3000);
            } catch (error) {
                let errorMessage = 'Erreur lors de l\'envoi';
                if (error.errorData?.error) {
                    const errors = error.errorData.error;
                    if (typeof errors === 'string') {
                        errorMessage = errors;
                    } else {
                        const firstField = Object.keys(errors)[0];
                        errorMessage = errors[firstField][0] || errorMessage;
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }
                errorEl.textContent = errorMessage;
                errorEl.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.querySelector('.btn-text').style.display = 'inline';
                btn.querySelector('.btn-spinner').style.display = 'none';
            }
        });

        document.getElementById('resetPasswordForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const btn = document.getElementById('resetPasswordSubmitBtn');
            const errorEl = document.getElementById('resetPasswordError');
            const token = document.getElementById('resetToken').value;
            const email = document.getElementById('resetEmail').value;
            const password = document.getElementById('resetNewPassword').value;
            const passwordConfirmation = document.getElementById('resetConfirmPassword').value;
            
            btn.disabled = true;
            btn.querySelector('.btn-text').style.display = 'none';
            btn.querySelector('.btn-spinner').style.display = 'inline-block';
            errorEl.style.display = 'none';

            try {
                const response = await apiRequest('/password/reset', {
                    method: 'POST',
                    body: JSON.stringify({
                        token,
                        email,
                        password,
                        password_confirmation: passwordConfirmation
                    })
                });
                alert(response.message);
                closeResetPasswordModal();
                window.location.href = '{{ route("mobile.login") }}';
            } catch (error) {
                let errorMessage = 'Erreur lors de la réinitialisation';
                if (error.errorData?.error) {
                    const errors = error.errorData.error;
                    if (typeof errors === 'string') {
                        errorMessage = errors;
                    } else {
                        const firstField = Object.keys(errors)[0];
                        errorMessage = errors[firstField][0] || errorMessage;
                    }
                } else if (error.message) {
                    errorMessage = error.message;
                }
                errorEl.textContent = errorMessage;
                errorEl.style.display = 'block';
            } finally {
                btn.disabled = false;
                btn.querySelector('.btn-text').style.display = 'inline';
                btn.querySelector('.btn-spinner').style.display = 'none';
            }
        });

        document.getElementById('forgotPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) closeForgotPasswordModal();
        });

        document.getElementById('resetPasswordModal').addEventListener('click', function(e) {
            if (e.target === this) closeResetPasswordModal();
        });
    </script>

    <!-- Forgot Password Modal -->
    <div class="modal-overlay" id="forgotPasswordModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeForgotPasswordModal()">
                <i class='bx bx-x'></i>
            </button>
            <div class="modal-header-mobile">
                <h2>Mot de passe oublié</h2>
            </div>
            <div class="modal-body-mobile">
                <p style="margin-bottom: 16px; color: var(--text-color);">Entrez votre adresse email pour recevoir un lien de réinitialisation.</p>
                <form id="forgotPasswordForm">
                    <div class="form-group">
                        <label class="form-label">Adresse email</label>
                        <input type="email" id="forgotPasswordEmail" class="form-input" 
                            placeholder="votre.email@gmail.com" required>
                    </div>
                    <div class="error-message" id="forgotPasswordError"></div>
                    <div class="success-message" id="forgotPasswordSuccess"></div>
                    <button type="submit" class="btn btn-primary" id="forgotPasswordSubmitBtn">
                        <span class="btn-text">Envoyer le lien</span>
                        <span class="btn-spinner" style="display: none;"><div class="spinner"></div></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Reset Password Modal -->
    <div class="modal-overlay" id="resetPasswordModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeResetPasswordModal()">
                <i class='bx bx-x'></i>
            </button>
            <div class="modal-header-mobile">
                <h2>Nouveau mot de passe</h2>
            </div>
            <div class="modal-body-mobile">
                <form id="resetPasswordForm">
                    <input type="hidden" id="resetToken">
                    <input type="hidden" id="resetEmail">
                    <div class="form-group">
                        <label class="form-label">Nouveau mot de passe</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="resetNewPassword" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="toggleResetPassword()">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Confirmer le mot de passe</label>
                        <div class="password-input-wrapper">
                            <input type="password" id="resetConfirmPassword" class="form-input" required>
                            <button type="button" class="password-toggle" onclick="toggleConfirmPassword()">
                                <i class='bx bx-show'></i>
                            </button>
                        </div>
                    </div>
                    <div class="error-message" id="resetPasswordError"></div>
                    <button type="submit" class="btn btn-primary" id="resetPasswordSubmitBtn">
                        <span class="btn-text">Réinitialiser</span>
                        <span class="btn-spinner" style="display: none;"><div class="spinner"></div></span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        function toggleResetPassword() {
            const input = document.getElementById('resetNewPassword');
            const toggleBtn = document.querySelector('#resetPasswordModal .password-toggle:first-of-type i');
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.className = 'bx bx-hide';
            } else {
                input.type = 'password';
                toggleBtn.className = 'bx bx-show';
            }
        }

        function toggleConfirmPassword() {
            const input = document.getElementById('resetConfirmPassword');
            const toggleBtn = document.querySelector('#resetPasswordModal .password-toggle:last-of-type i');
            if (input.type === 'password') {
                input.type = 'text';
                toggleBtn.className = 'bx bx-hide';
            } else {
                input.type = 'password';
                toggleBtn.className = 'bx bx-show';
            }
        }

        // Check for reset password URL parameters
        document.addEventListener('DOMContentLoaded', function() {
            const urlParams = new URLSearchParams(window.location.search);
            const token = urlParams.get('token');
            const email = urlParams.get('email');
            
            if (token && email) {
                document.getElementById('resetToken').value = token;
                document.getElementById('resetEmail').value = email;
                openResetPasswordModal();
            }
        });
    </script>

</body>

</html>
