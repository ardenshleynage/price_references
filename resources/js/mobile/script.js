// Get configuration from body data attributes
const body = document.body;
// const API_URL = body.dataset.apiUrl || '/api';
const API_URL = window.location.origin + '/api';
const MOBILE_LOGIN_URL = body.dataset.loginUrl || '/mobile/login';
const MOBILE_DASHBOARD_URL = body.dataset.dashboardUrl || '/mobile/dashboard';

// Make URLs available globally
window.API_URL = API_URL;
window.MOBILE_LOGIN_URL = MOBILE_LOGIN_URL;
window.MOBILE_DASHBOARD_URL = MOBILE_DASHBOARD_URL;

// Get stored user data
function getUser() {
    return {
        id: localStorage.getItem('mobile_user_id'),
        token: localStorage.getItem('mobile_token'),
        username: localStorage.getItem('mobile_username'),
        role: localStorage.getItem('mobile_role')
    };
}

// Clear user data (logout)
function clearUser() {
    localStorage.removeItem('mobile_user_id');
    localStorage.removeItem('mobile_token');
    localStorage.removeItem('mobile_username');
    localStorage.removeItem('mobile_role');
}

window.clearUser = clearUser;

// Check if user is logged in
function isLoggedIn() {
    const user = getUser();
    return user.id && user.token;
}

// Redirect to login if not logged in
function requireAuth() {
    if (!isLoggedIn()) {
        window.location.href = MOBILE_LOGIN_URL;
        return false;
    }
    return true;
}

// Show toast message
function showToast(message) {
    const existingToast = document.querySelector('.toast');
    if (existingToast) {
        existingToast.remove();
    }

    const toast = document.createElement('div');
    toast.className = 'toast';
    toast.textContent = message;
    document.body.appendChild(toast);

    setTimeout(() => {
        toast.style.opacity = '0';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

// Logout
function logout() {
    localStorage.removeItem('mobile_user_id');
    localStorage.removeItem('mobile_token');
    localStorage.removeItem('mobile_username');
    localStorage.removeItem('mobile_role');
    window.location.href = MOBILE_LOGIN_URL;
}

// Theme Toggle
function toggleTheme() {
    const profileToggle = document.getElementById('themeToggle');
    const isDark = profileToggle ? profileToggle.checked : document.documentElement.classList.toggle('dark');

    if (profileToggle) {
        profileToggle.checked = isDark;
    }

    if (isDark) {
        document.documentElement.classList.add('dark');
    } else {
        document.documentElement.classList.remove('dark');
    }

    const themeValue = isDark ? 'dark' : 'light';
    localStorage.setItem('mobile_theme', themeValue);
    updateThemeIcon();
    updateProfileThemeToggle();

    apiRequest('/user/update/theme', {
        method: 'PUT',
        body: JSON.stringify({ theme: themeValue })
    }).catch(err => console.error('Failed to save theme:', err));

    window.dispatchEvent(new CustomEvent('themeChanged', { detail: { isDark } }));
}

function updateThemeIcon() {
    const isDark = document.documentElement.classList.contains('dark');
    const themeBtn = document.getElementById('themeToggleBtn');
    if (themeBtn) {
        themeBtn.innerHTML = isDark ? '<i class="bx bxs-sun"></i>' : '<i class="bx bxs-moon"></i>';
    }
}

function updateProfileThemeToggle() {
    const isDark = document.documentElement.classList.contains('dark');
    const themeToggle = document.getElementById('themeToggle');
    const themeLabel = document.getElementById('themeLabel');
    const themeIcon = document.getElementById('themeIcon');

    if (themeToggle) {
        themeToggle.checked = isDark;
    }
    if (themeLabel) {
        themeLabel.textContent = isDark ? 'Mode sombre' : 'Mode clair';
    }
    if (themeIcon) {
        themeIcon.className = isDark ? 'bx bxs-sun' : 'bx bxs-moon';
    }
}

function initTheme() {
    const savedTheme = localStorage.getItem('mobile_theme') || 'light';
    if (savedTheme === 'dark') {
        document.documentElement.classList.add('dark');
    }

    const profileToggle = document.getElementById('themeToggle');
    if (profileToggle) {
        profileToggle.checked = savedTheme === 'dark';
    }

    updateThemeIcon();
    updateProfileThemeToggle();
}

// API request helper
async function apiRequest(endpoint, options = {}) {
    const user = getUser();
    const defaultOptions = {
        headers: {
            'Content-Type': 'application/json',
            'X-User-ID': user.id || '',
            'X-Token': user.token || ''
        }
    };

    const response = await fetch(API_URL + endpoint, {
        ...defaultOptions,
        ...options,
        headers: {
            ...defaultOptions.headers,
            ...(options.headers || {})
        }
    });

    if (!response.ok) {
        if (response.status === 401) {
            logout();
        }
        const errorData = await response.json().catch(() => ({}));
        console.log('API Error:', response.status, errorData);
        const errorMessage = typeof errorData.error === 'string'
            ? errorData.error
            : (errorData.message || 'Request failed');
        const error = new Error(errorMessage);
        error.response = response;
        error.errorData = errorData;
        throw error;
    }

    return response.json();
}

// Initialize theme on load
document.addEventListener('DOMContentLoaded', function() {
    initTheme();
});

// Make functions globally available
window.getUser = getUser;
window.isLoggedIn = isLoggedIn;
window.requireAuth = requireAuth;
window.showToast = showToast;
window.logout = logout;
window.toggleTheme = toggleTheme;
window.updateThemeIcon = updateThemeIcon;
window.updateProfileThemeToggle = updateProfileThemeToggle;
window.initTheme = initTheme;
window.apiRequest = apiRequest;
window.loadDashboardStats = loadDashboardStats;

// ==========================================
// Page-specific scripts
// ==========================================

// Login Page Script
function initLogin() {
    // Check if already logged in
    if (localStorage.getItem('mobile_token')) {
        window.location.href = window.MOBILE_DASHBOARD_URL || '/mobile/dashboard';
        return;
    }

    const loginForm = document.getElementById('loginForm');
    if (!loginForm) return;

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const formData = new FormData(e.target);
        const submitBtn = e.target.querySelector('button[type="submit"]');
        const errorMessage = document.getElementById('errorMessage');

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner"></span> Connexion...';
        errorMessage.style.display = 'none';

        try {
            const response = await fetch(API_URL + '/login', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    username: formData.get('username'),
                    password: formData.get('password')
                })
            });

            const data = await response.json();

            if (data.success) {
                localStorage.setItem('mobile_user_id', data.user.id);
                localStorage.setItem('mobile_token', data.token);
                localStorage.setItem('mobile_username', data.user.username);
                localStorage.setItem('mobile_role', data.user.role);

                window.location.href = window.MOBILE_DASHBOARD_URL || '/mobile/dashboard';
            } else {
                errorMessage.textContent = data.error || 'Identifiants invalides';
                errorMessage.style.display = 'block';
                submitBtn.disabled = false;
                submitBtn.innerHTML = '<i class="bx bxs-log-in"></i> Se connecter';
            }
        } catch (error) {
            errorMessage.textContent = 'Erreur de connexion au serveur';
            errorMessage.style.display = 'block';
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bx bxs-log-in"></i> Se connecter';
        }
    });
}

// Dashboard Page Script
function initDashboard() {
    const user = getUser();

    // Check if user is logged in
    if (!user.id || !user.token) {
        const loadingState = document.getElementById('loadingState');
        const notAuthContent = document.getElementById('notAuthContent');

        if (loadingState) loadingState.style.display = 'none';
        if (notAuthContent) notAuthContent.style.display = 'block';
        return;
    }

    // Update welcome message
    const welcomeUsername = document.getElementById('welcomeUsername');
    const welcomeRole = document.getElementById('welcomeRole');
    const loadingState = document.getElementById('loadingState');
    const authenticatedContent = document.getElementById('authenticatedContent');

    if (welcomeUsername) welcomeUsername.textContent = user.username;

    // Set role badge
    let roleText = '';
    switch(parseInt(user.role)) {
        case 1: roleText = 'Super Administrateur'; break;
        case 2: roleText = 'Administrateur'; break;
        case 3: roleText = 'Lecteur'; break;
    }
    if (welcomeRole) welcomeRole.textContent = roleText;

    // Show authenticated content
    if (loadingState) loadingState.style.display = 'none';
    if (authenticatedContent) authenticatedContent.style.display = 'block';

    // Load stats based on role
    loadDashboardStats();
}

async function loadDashboardStats() {
    const productsCount = document.getElementById('productsCount');
    const categoriesCount = document.getElementById('categoriesCount');
    const branchesCount = document.getElementById('branchesCount');
    const usersCount = document.getElementById('usersCount');
    const usersStatCard = document.getElementById('usersStatCard');

    if (!productsCount) return;

    try {
        const stats = await apiRequest('/dashboard/stats');

        // Update counts
        if (productsCount) productsCount.textContent = stats.products?.total || 0;
        if (categoriesCount) categoriesCount.textContent = stats.categories?.total || 0;
        if (branchesCount) branchesCount.textContent = stats.branches?.total || 0;

        // Show users stat card only for Super Admin (role 1)
        if (usersStatCard && usersCount) {
            if (stats.role == 1) {
                usersStatCard.style.display = 'block';
                usersCount.textContent = stats.users?.total || 0;
            } else {
                usersStatCard.style.display = 'none';
            }
        }
    } catch (error) {
        console.error('Error loading stats:', error);
        showToast('Erreur lors du chargement des statistiques');
    }
}

// Initialize page-specific scripts based on body class or data attribute
document.addEventListener('DOMContentLoaded', function() {
    initTheme();

    // Auto-detect which page we're on and initialize accordingly
    const body = document.body;

    // Login page
    if (document.getElementById('loginForm')) {
        initLogin();
    }

    // Dashboard page
    if (body.classList.contains('page-dashboard') || document.getElementById('mainContainer')) {
        if (document.getElementById('loadingState')) {
            initDashboard();
        }
    }
});
