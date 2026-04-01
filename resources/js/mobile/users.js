let currentStatus = 'all';
let selectedUser = null;
let searchQuery = '';

window.initPage = async function() {
    const user = getUser();
    window.userRole = parseInt(user.role) || 3;
    console.log('Users page - User role set to:', window.userRole);
    
    const addUserBtn = document.getElementById('addUserBtn');
    if (!addUserBtn) return;

    if (window.userRole !== 1) {
        document.getElementById('loadingState').style.display = 'none';
        document.getElementById('accessDeniedContent').style.display = 'block';
        return;
    }

    addUserBtn.style.display = 'flex';
    currentStatus = 'all';
    loadTabs();
    loadUsers();
}

window.openAddUserModal = async function() {
    const modal = document.getElementById('addUserModal');
    const form = document.getElementById('addUserForm');
    form.reset();
    document.getElementById('addUserError').style.display = 'none';
    document.querySelector('#roleSelect .custom-select-value').textContent = '- Sélectionner un rôle -';
    closeAllCustomSelects();
    modal.classList.add('active');
}

window.closeAddUserModal = function() {
    document.getElementById('addUserModal').classList.remove('active');
}

window.toggleCustomSelect = function(selectId) {
    const allSelects = document.querySelectorAll('.custom-select');
    allSelects.forEach(select => {
        if (select.id !== selectId) {
            select.classList.remove('active');
        }
    });

    const select = document.getElementById(selectId);
    select.classList.toggle('active');
}

window.selectOption = function(selectId, value, text) {
    const select = document.getElementById(selectId);
    const hiddenInput = select.querySelector('input[type="hidden"]');
    const valueSpan = select.querySelector('.custom-select-value');

    hiddenInput.value = value;
    valueSpan.textContent = text;
    select.classList.remove('active');
}

window.closeAllCustomSelects = function() {
    document.querySelectorAll('.custom-select').forEach(select => {
        select.classList.remove('active');
    });
}

document.addEventListener('click', function(e) {
    if (!e.target.closest('.custom-select')) {
        closeAllCustomSelects();
    }
});

const addUserForm = document.getElementById('addUserForm');
if (addUserForm) {
    addUserForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const username = document.getElementById('addUsername').value;
    const email = document.getElementById('addEmail').value;
    const password = document.getElementById('addPassword').value;
    const role = document.getElementById('addRole').value;
    const errorDiv = document.getElementById('addUserError');
    const submitBtn = document.getElementById('addUserSubmitBtn');

    if (!username || !email || !password || !role) {
        errorDiv.textContent = 'Veuillez remplir tous les champs obligatoires.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        const data = await apiRequest('/users/create', {
            method: 'POST',
            body: JSON.stringify({
                username: username,
                email: email,
                password: password,
                role: role
            })
        });

        showToast('Utilisateur ajouté avec succès');
        closeAddUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        let errorMessage = 'Erreur lors de la création de l\'utilisateur';
        if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error;
        } else if (error.message) {
            errorMessage = error.message;
        }
        errorDiv.textContent = errorMessage;
        errorDiv.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="btn-text">Ajouter</span>';
    }
    });
}

const addUserModal = document.getElementById('addUserModal');
if (addUserModal) {
    addUserModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeAddUserModal();
        }
    });
}

window.loadUsers = async function() {
    const loadingState = document.getElementById('loadingState');
    const notAuthContent = document.getElementById('notAuthContent');
    const authenticatedContent = document.getElementById('authenticatedContent');
    const dataList = document.getElementById('dataList');
    const itemCount = document.getElementById('itemCount');
    const paginationInfo = document.getElementById('paginationInfo');

    const user = getUser();
    if (!user.id || !user.token) {
        loadingState.style.display = 'none';
        notAuthContent.style.display = 'block';
        return;
    }

    if (window.userRole !== 1) {
        loadingState.style.display = 'none';
        document.getElementById('accessDeniedContent').style.display = 'block';
        return;
    }

    try {
        let url = '/users?status=' + currentStatus;
        if (currentStatus === 'all') {
            url = '/users?status=all';
        }
        if (searchQuery) {
            url += '&search=' + encodeURIComponent(searchQuery);
        }

        const data = await apiRequest(url);
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';

        if (data.data && data.data.length > 0) {
            itemCount.textContent = data.total + ' utilisateur(s)';

            dataList.innerHTML = data.data.map(userItem => `
                <div class="data-card" onclick="openUserModal(${userItem.id})" style="cursor: pointer;">
                    <div class="data-card-header">
                        <h3>${userItem.username || '-'}</h3>
                        <span class="badge ${getStatusBadgeClass(userItem.status)}">
                            ${getStatusLabel(userItem.status)}
                        </span>
                    </div>
                    <div class="data-card-body">
                        <div class="data-item">
                            <span class="data-label">E-mail:</span>
                            <span class="data-value">${userItem.email || '-'}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Rôle:</span>
                            <span class="data-value">${getRoleLabel(userItem.role)}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Dernière connexion:</span>
                            <span class="data-value">${formatDate(userItem.last_time_connect)}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Modifié:</span>
                            <span class="data-value">${formatDate(userItem.updated_at)}</span>
                        </div>
                    </div>
                </div>
            `).join('');

            if (data.last_page > 1) {
                paginationInfo.style.display = 'block';
                paginationInfo.innerHTML = `Page ${data.current_page} sur ${data.last_page}`;
            } else {
                paginationInfo.style.display = 'none';
            }
        } else {
            dataList.innerHTML = '<p class="empty-message">Aucun utilisateur trouvé.</p>';
            paginationInfo.style.display = 'none';
        }
    } catch (error) {
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';
        dataList.innerHTML = '<p class="empty-message">Erreur lors du chargement des utilisateurs.</p>';
        showToast('Erreur lors du chargement des utilisateurs');
    }
}

window.openUserModal = async function(userId) {
    const modal = document.getElementById('userModal');
    const user = getUser();

    try {
        let url = '/users?status=all';
        const data = await apiRequest(url);
        const userItem = data.data.find(u => u.id === userId);

        if (!userItem) {
            showToast('Utilisateur non trouvé');
            return;
        }

        selectedUser = userItem;

        document.getElementById('modalUsername').textContent = userItem.username || '-';
        document.getElementById('modalEmail').textContent = userItem.email || '-';
        document.getElementById('modalRole').textContent = getRoleLabel(userItem.role);
        document.getElementById('modalLastConnection').textContent = formatDate(userItem.last_time_connect);
        document.getElementById('modalCreatedAt').textContent = formatDate(userItem.created_at);
        document.getElementById('modalUpdatedAt').textContent = formatDate(userItem.updated_at);

        const statusBadge = document.getElementById('modalStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getStatusBadgeClass(userItem.status);
        statusBadge.textContent = getStatusLabel(userItem.status);

        const actionsDiv = document.getElementById('modalActions');
        let actionsHtml = '';
        const userStatus = parseInt(userItem.status);

        if (userStatus === 1) {
            actionsHtml += `
                <button class="btn btn-primary" onclick="openEditUserModal(${userItem.id})"><i class='bx bxs-edit'></i> Modifier</button>
                <button class="btn btn-warning" onclick="blockUser(${userItem.id})"><i class='bx bxs-block'></i> Bloquer</button>
                <button class="btn btn-danger-outline" onclick="deleteUser(${userItem.id})"><i class='bx bxs-trash'></i> Supprimer</button>
            `;
        } else if (userStatus === 2) {
            actionsHtml += `
                <button class="btn btn-primary" onclick="openEditUserModal(${userItem.id})"><i class='bx bxs-edit'></i> Modifier</button>
                <button class="btn btn-success" onclick="unblockUser(${userItem.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                <button class="btn btn-danger-outline" onclick="deleteUser(${userItem.id})"><i class='bx bxs-trash'></i> Supprimer</button>
            `;
        } else if (userStatus === 0) {
            actionsHtml += `
                <button class="btn btn-primary" onclick="openEditUserModal(${userItem.id})"><i class='bx bxs-edit'></i> Modifier</button>
                <button class="btn btn-success" onclick="restoreUser(${userItem.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                <button class="btn btn-danger" onclick="openConfirmEraseModal(${userItem.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
            `;
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de l\'utilisateur');
    }
}

window.closeUserModal = function() {
    document.getElementById('userModal').classList.remove('active');
    selectedUser = null;
}

window.closeConfirmEraseModal = function() {
    document.getElementById('confirmEraseModal').classList.remove('active');
}

window.blockUser = async function(id) {
    try {
        await apiRequest('/users/block', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Utilisateur bloqué');
        closeUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockUser = async function(id) {
    try {
        await apiRequest('/users/unblock', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Utilisateur débloqué');
        closeUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteUser = async function(id) {
    try {
        await apiRequest('/users/delete', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Utilisateur supprimé');
        closeUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreUser = async function(id) {
    try {
        await apiRequest('/users/restore', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Utilisateur restauré');
        closeUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.openConfirmEraseModal = function(id) {
    selectedUser = {
        id: id
    };
    document.getElementById('confirmEraseModal').classList.add('active');
}

window.openEditUserModal = async function(id) {
    const modal = document.getElementById('editUserModal');
    const form = document.getElementById('editUserForm');
    form.reset();
    document.getElementById('editUserError').style.display = 'none';
    document.querySelector('#editRoleSelect .custom-select-value').textContent = '- Sélectionner un rôle -';
    closeUserModal();
    closeAllCustomSelects();

    try {
        const data = await apiRequest('/users?status=all');
        const userItem = data.data.find(u => u.id === id);

        if (!userItem) {
            showToast('Utilisateur non trouvé');
            return;
        }

        document.getElementById('editUserId').value = userItem.id;
        document.getElementById('editUsername').value = userItem.username || '';
        document.getElementById('editEmail').value = userItem.email || '';

        if (userItem.role) {
            document.getElementById('editRole').value = userItem.role;
            document.getElementById('editRoleValue').textContent = getRoleLabel(userItem.role);
            document.querySelector('#editRoleSelect .custom-select-value').textContent = getRoleLabel(userItem.role);
        }

        modal.classList.add('active');
    } catch (error) {
        console.error('Error:', error);
        showToast('Erreur lors du chargement de l\'utilisateur');
    }
}

window.closeEditUserModal = function() {
    document.getElementById('editUserModal').classList.remove('active');
}

window.selectEditRole = function(value, text) {
    const editRole = document.getElementById('editRole');
    const editRoleValue = document.getElementById('editRoleValue');
    const editRoleSelect = document.getElementById('editRoleSelect');
    if (editRole) editRole.value = value;
    if (editRoleValue) editRoleValue.textContent = text;
    const roleSelectValue = document.querySelector('#editRoleSelect .custom-select-value');
    if (roleSelectValue) roleSelectValue.textContent = text;
    if (editRoleSelect) editRoleSelect.classList.remove('active');
}

const editUserForm = document.getElementById('editUserForm');
if (editUserForm) {
    editUserForm.addEventListener('submit', async function(e) {
    e.preventDefault();

    const userId = document.getElementById('editUserId').value;
    const username = document.getElementById('editUsername').value;
    const email = document.getElementById('editEmail').value;
    const role = document.getElementById('editRole').value;
    const errorDiv = document.getElementById('editUserError');
    const submitBtn = document.getElementById('editUserSubmitBtn');

    if (!username && !email && !role) {
        errorDiv.textContent = 'Veuillez remplir au moins un champ à modifier.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        const requestBody = { user_id: userId };
        if (username) requestBody.username = username;
        if (email) requestBody.email = email;
        if (role) requestBody.role = role;

        const data = await apiRequest('/users/update', {
            method: 'POST',
            body: JSON.stringify(requestBody)
        });

        showToast('Utilisateur modifié avec succès');
        closeEditUserModal();
        loadUsers();
        loadTabs();
        const searchInput = document.getElementById('searchInput');
        if (searchInput && searchInput.value.trim()) {
            performSearch(searchInput.value.trim());
        }
    } catch (error) {
        let errorMessage = 'Erreur lors de la modification de l\'utilisateur';
        if (error.response && error.response.data && error.response.data.error) {
            errorMessage = error.response.data.error;
        } else if (error.message) {
            errorMessage = error.message;
        }
        errorDiv.textContent = errorMessage;
        errorDiv.style.display = 'block';
    } finally {
        submitBtn.disabled = false;
        submitBtn.innerHTML = '<span class="btn-text">Enregistrer</span>';
    }
    });
}

const editUserModal = document.getElementById('editUserModal');
if (editUserModal) {
    editUserModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeEditUserModal();
        }
    });
}

window.confirmEraseUser = async function() {
    if (!selectedUser) return;

    try {
        await apiRequest('/users/erase', {
            method: 'POST',
            body: JSON.stringify({
                id: selectedUser.id
            })
        });
        showToast('Utilisateur supprimé définitivement');
        closeConfirmEraseModal();
        closeUserModal();
        loadUsers();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

const userModal = document.getElementById('userModal');
if (userModal) {
    userModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeUserModal();
        }
    });
}

const confirmEraseModal = document.getElementById('confirmEraseModal');
if (confirmEraseModal) {
    confirmEraseModal.addEventListener('click', function(e) {
        if (e.target === this) {
            closeConfirmEraseModal();
        }
    });
}

window.loadTabs = async function() {
    const statusTabs = document.getElementById('statusTabs');
    
    if (userRole === 3) {
        statusTabs.style.display = 'none';
        return;
    }
    
    statusTabs.style.display = 'flex';

    try {
        const counts = await apiRequest('/users/counts');

        let tabsHtml = '';

        tabsHtml += `
            <button class="status-tab ${currentStatus === 'all' ? 'active' : ''}" onclick="changeStatus('all')">
                Tous(${counts.total})
            </button>
        `;

        tabsHtml += `
            <button class="status-tab ${currentStatus === 1 ? 'active' : ''}" onclick="changeStatus(1)">
                Actif(${counts.active})
            </button>
        `;

        tabsHtml += `
            <button class="status-tab ${currentStatus === 2 ? 'active' : ''}" onclick="changeStatus(2)">
                Bloqué(${counts.blocked})
            </button>
        `;

        tabsHtml += `
            <button class="status-tab ${currentStatus === 0 ? 'active' : ''}" onclick="changeStatus(0)">
                Corbeille(${counts.deleted})
            </button>
        `;

        statusTabs.innerHTML = tabsHtml;
    } catch (error) {
        console.error('Error loading counts:', error);
    }
}

window.changeStatus = function(status) {
    currentStatus = status;
    loadUsers();
    loadTabs();
}

window.getStatusBadgeClass = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'badge-active';
    if (s === 2) return 'badge-blocked';
    if (s === 0) return 'badge-deleted';
    return 'badge-active';
}

window.getStatusLabel = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'Actif';
    if (s === 2) return 'Bloqué';
    if (s === 0) return 'Supprimé';
    return 'Inconnu';
}

window.getRoleLabel = function(role) {
    const r = parseInt(role);
    if (r === 1) return 'Super Admin';
    if (r === 2) return 'Administrateur';
    if (r === 3) return 'Lecteur';
    return 'Inconnu';
}

window.formatDate = function(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

document.addEventListener('DOMContentLoaded', function() {
    initPage();
    
    const searchInput = document.getElementById('userSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', function() {
            handleUserSearch(this.value);
        });
    }
});

let userSearchTimeout = null;

window.handleUserSearch = function(query) {
    clearTimeout(userSearchTimeout);
    userSearchTimeout = setTimeout(() => {
        searchQuery = query.trim();
        currentStatus = 'all';
        loadUsers();
        loadTabs();
    }, 300);
}
