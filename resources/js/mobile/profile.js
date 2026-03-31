let currentUserData = {};

window.loadProfile = async function() {
    const loadingState = document.getElementById('loadingState');
    const notAuthContent = document.getElementById('notAuthContent');
    const authenticatedContent = document.getElementById('authenticatedContent');

    const user = getUser();
    if (!user.id || !user.token) {
        loadingState.style.display = 'none';
        notAuthContent.style.display = 'block';
        return;
    }

    loadingState.style.display = 'none';
    authenticatedContent.style.display = 'block';

    const isDark = document.documentElement.classList.contains('dark');
    const navbarToggle = document.getElementById('navbarThemeToggle');
    const profileToggle = document.getElementById('themeToggle');
    
    if (navbarToggle) {
        navbarToggle.checked = isDark;
    }
    if (profileToggle) {
        profileToggle.checked = isDark;
    }

    document.getElementById('profileUsername').textContent = user.username;
    document.getElementById('infoUsername').textContent = user.username;

    let roleText = '';
    let roleStyle = '';
    switch (parseInt(user.role)) {
        case 1:
            roleText = 'Super Administrateur';
            roleStyle = 'background: #667eea; color: white;';
            break;
        case 2:
            roleText = 'Administrateur';
            roleStyle = 'background: #10b981; color: white;';
            break;
        case 3:
            roleText = 'Lecteur';
            roleStyle = 'background: #6b7280; color: white;';
            break;
    }
    document.getElementById('profileRole').textContent = roleText;
    document.getElementById('profileRole').style.cssText = roleStyle;

    try {
        const userData = await apiRequest('/user');
        currentUserData = userData;
        document.getElementById('infoEmail').textContent = userData.email || 'Non disponible';
        localStorage.setItem('mobile_username', userData.username);
    } catch (error) {
        console.error('Error loading profile:', error);
        document.getElementById('infoEmail').textContent = 'Non disponible';
    }
}

window.openLogoutModal = function() {
    document.getElementById('logoutModal').classList.add('active');
}

window.closeLogoutModal = function() {
    document.getElementById('logoutModal').classList.remove('active');
}

window.confirmLogout = function() {
    clearUser();
    window.location.href = window.MOBILE_LOGIN_URL || '/mobile/login';
}

window.openEditUsernameModal = function() {
    document.getElementById('editUsernameModal').classList.add('active');
    document.getElementById('editUsernameInput').value = currentUserData.username || '';
    document.getElementById('editUsernameError').style.display = 'none';
}

window.closeEditUsernameModal = function() {
    document.getElementById('editUsernameModal').classList.remove('active');
}

window.openEditEmailModal = function() {
    document.getElementById('editEmailModal').classList.add('active');
    document.getElementById('editEmailInput').value = currentUserData.email || '';
    document.getElementById('editEmailError').style.display = 'none';
}

window.closeEditEmailModal = function() {
    document.getElementById('editEmailModal').classList.remove('active');
}

window.openChangePasswordModal = function() {
    document.getElementById('changePasswordModal').classList.add('active');
    document.getElementById('currentPassword').value = '';
    document.getElementById('newPassword').value = '';
    document.getElementById('confirmPassword').value = '';
    document.getElementById('changePasswordError').style.display = 'none';
}

window.closeChangePasswordModal = function() {
    document.getElementById('changePasswordModal').classList.remove('active');
}

window.togglePasswordVisibility = function(inputId, toggleId) {
    const input = document.getElementById(inputId);
    const toggle = document.getElementById(toggleId);
    if (input.type === 'password') {
        input.type = 'text';
        toggle.classList.remove('bx-show');
        toggle.classList.add('bx-hide');
    } else {
        input.type = 'password';
        toggle.classList.remove('bx-hide');
        toggle.classList.add('bx-show');
    }
}

document.getElementById('editUsernameForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('editUsernameSubmitBtn');
    const errorEl = document.getElementById('editUsernameError');
    const newUsername = document.getElementById('editUsernameInput').value.trim();
    
    if (!newUsername) return;
    
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-spinner').style.display = 'inline-block';
    errorEl.style.display = 'none';

    try {
        const response = await apiRequest('/user/update/username', {
            method: 'PUT',
            body: JSON.stringify({ username: newUsername })
        });
        currentUserData.username = newUsername;
        document.getElementById('profileUsername').textContent = newUsername;
        document.getElementById('infoUsername').textContent = newUsername;
        localStorage.setItem('mobile_username', newUsername);
        const user = getUser();
        user.username = newUsername;
        localStorage.setItem('mobile_user', JSON.stringify(user));
        closeEditUsernameModal();
    } catch (error) {
        console.log('Username update error:', error);
        let errorMessage = 'Erreur lors de la mise à jour';
        
        if (error.errorData) {
            if (typeof error.errorData.error === 'string') {
                errorMessage = error.errorData.error;
            } else if (error.errorData.error && typeof error.errorData.error === 'object') {
                const errors = error.errorData.error;
                const firstField = Object.keys(errors)[0];
                errorMessage = errors[firstField][0] || errorMessage;
            } else if (error.errorData.message) {
                errorMessage = error.errorData.message;
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

document.getElementById('editEmailForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('editEmailSubmitBtn');
    const errorEl = document.getElementById('editEmailError');
    const newEmail = document.getElementById('editEmailInput').value.trim();
    
    if (!newEmail) return;
    
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-spinner').style.display = 'inline-block';
    errorEl.style.display = 'none';

    try {
        const response = await apiRequest('/user/update/email', {
            method: 'PUT',
            body: JSON.stringify({ email: newEmail })
        });
        currentUserData.email = newEmail;
        document.getElementById('infoEmail').textContent = newEmail;
        closeEditEmailModal();
    } catch (error) {
        if (error.errorData && error.errorData.error) {
            const errors = error.errorData.error;
            if (typeof errors === 'string') {
                errorEl.textContent = errors;
            } else {
                const firstField = Object.keys(errors)[0];
                errorEl.textContent = errors[firstField][0] || 'Erreur de validation';
            }
        } else {
            errorEl.textContent = error.message || 'Erreur lors de la mise à jour';
        }
        errorEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = 'inline';
        btn.querySelector('.btn-spinner').style.display = 'none';
    }
});

document.getElementById('changePasswordForm').addEventListener('submit', async function(e) {
    e.preventDefault();
    const btn = document.getElementById('changePasswordSubmitBtn');
    const errorEl = document.getElementById('changePasswordError');
    const currentPassword = document.getElementById('currentPassword').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;
    
    if (newPassword !== confirmPassword) {
        errorEl.textContent = 'Les mots de passe ne correspondent pas';
        errorEl.style.display = 'block';
        return;
    }
    
    btn.disabled = true;
    btn.querySelector('.btn-text').style.display = 'none';
    btn.querySelector('.btn-spinner').style.display = 'inline-block';
    errorEl.style.display = 'none';

    try {
        const response = await apiRequest('/user/update/password', {
            method: 'PUT',
            body: JSON.stringify({
                current_password: currentPassword,
                new_password: newPassword,
                confirm_password: confirmPassword
            })
        });
        closeChangePasswordModal();
    } catch (error) {
        if (error.errorData && error.errorData.error) {
            const errors = error.errorData.error;
            if (typeof errors === 'string') {
                errorEl.textContent = errors;
            } else {
                const firstField = Object.keys(errors)[0];
                errorEl.textContent = errors[firstField][0] || 'Erreur de validation';
            }
        } else {
            errorEl.textContent = error.message || 'Erreur lors de la mise à jour';
        }
        errorEl.style.display = 'block';
    } finally {
        btn.disabled = false;
        btn.querySelector('.btn-text').style.display = 'inline';
        btn.querySelector('.btn-spinner').style.display = 'none';
    }
});

document.getElementById('logoutModal').addEventListener('click', function(e) {
    if (e.target === this) closeLogoutModal();
});

document.getElementById('editUsernameModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditUsernameModal();
});

document.getElementById('editEmailModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditEmailModal();
});

document.getElementById('changePasswordModal').addEventListener('click', function(e) {
    if (e.target === this) closeChangePasswordModal();
});

document.addEventListener('DOMContentLoaded', function() {
    loadProfile();
});