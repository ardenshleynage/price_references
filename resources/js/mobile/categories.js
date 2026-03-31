let currentStatus;
let userRole = 3;
let selectedCategory = null;

window.initPage = async function() {
    const user = getUser();
    userRole = parseInt(user.role) || 3;

    if (userRole === 1 || userRole === 2) {
        currentStatus = 'all';
        document.getElementById('addCategoryBtn').style.display = 'flex';
    } else {
        currentStatus = 1;
    }

    loadTabs();
    loadCategories();
}

window.loadCategories = async function() {
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

    try {
        let url = '/categories?status=' + currentStatus;
        if (currentStatus === 'all') {
            url = '/categories?status=all';
        }

        const data = await apiRequest(url);
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';

        if (data.data && data.data.length > 0) {
            itemCount.textContent = data.total + ' catégorie(s)';

            dataList.innerHTML = data.data.map(category => `
                <div class="data-card" onclick="openCategoryModal(${category.id})" style="cursor: pointer;">
                    <div class="data-card-header">
                        <h3>${category.category_name || '-'}</h3>
                        <span class="badge ${getCategoryStatusBadgeClass(category.status)}">
                            ${getCategoryStatusLabel(category.status)}
                        </span>
                    </div>
                    <div class="data-card-body">
                        <div class="data-item">
                            <span class="data-label">Créé le:</span>
                            <span class="data-value">${formatDate(category.created_at)}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Modifié le:</span>
                            <span class="data-value">${formatDate(category.updated_at)}</span>
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
            dataList.innerHTML = '<p class="empty-message">Aucune catégorie trouvée.</p>';
            paginationInfo.style.display = 'none';
        }
    } catch (error) {
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';
        dataList.innerHTML = '<p class="empty-message">Erreur lors du chargement des catégories.</p>';
        showToast('Erreur lors du chargement des catégories');
    }
}

window.openCategoryModal = async function(categoryId) {
    const modal = document.getElementById('categoryModal');
    const user = getUser();

    try {
        let url = '/categories?status=all';
        const data = await apiRequest(url);
        const category = data.data.find(c => c.id === categoryId);

        if (!category) {
            showToast('Catégorie non trouvée');
            return;
        }

        selectedCategory = category;

        document.getElementById('modalCategoryName').textContent = category.category_name || '-';

        const statusBadge = document.getElementById('modalCategoryStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getCategoryStatusBadgeClass(category.status);
        statusBadge.textContent = getCategoryStatusLabel(category.status);

        document.getElementById('modalCategoryCreatedAt').textContent = formatDate(category.created_at);
        document.getElementById('modalCategoryUpdatedAt').textContent = formatDate(category.updated_at);

        const actionsDiv = document.getElementById('modalCategoryActions');
        let actionsHtml = '';
        const categoryStatus = parseInt(category.status);

        if (userRole === 1) {
            if (categoryStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockCategory(${category.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategory(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockCategory(${category.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategory(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategory(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseCategoryModal(${category.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 2) {
            if (categoryStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategory(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategory(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            } else if (categoryStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategory(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            }
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la catégorie');
    }
}

window.closeCategoryModal = function() {
    document.getElementById('categoryModal').classList.remove('active');
    selectedCategory = null;
}

window.closeConfirmEraseCategoryModal = function() {
    document.getElementById('confirmEraseCategoryModal').classList.remove('active');
}

window.openConfirmEraseCategoryModal = function(id) {
    selectedCategory = { id: id };
    document.getElementById('confirmEraseCategoryModal').classList.add('active');
}

window.openAddCategoryModal = async function() {
    const modal = document.getElementById('addCategoryModal');
    const form = document.getElementById('addCategoryForm');
    form.reset();
    document.getElementById('addCategoryError').style.display = 'none';
    modal.classList.add('active');
}

window.closeAddCategoryModal = function() {
    document.getElementById('addCategoryModal').classList.remove('active');
}

window.openEditCategoryModal = async function(id) {
    const modal = document.getElementById('editCategoryModal');
    const form = document.getElementById('editCategoryForm');
    form.reset();
    document.getElementById('editCategoryError').style.display = 'none';

    closeCategoryModal();

    try {
        const data = await apiRequest('/categories?status=all');
        const category = data.data.find(c => c.id === id);

        if (!category) {
            showToast('Catégorie non trouvée');
            return;
        }

        document.getElementById('editCategoryId').value = category.id;
        document.getElementById('editCategoryName').value = category.category_name || '';

        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la catégorie');
    }
}

window.closeEditCategoryModal = function() {
    document.getElementById('editCategoryModal').classList.remove('active');
}

window.blockCategory = async function(id) {
    try {
        await apiRequest('/categories/block', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie bloquée');
        closeCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockCategory = async function(id) {
    try {
        await apiRequest('/categories/unblock', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie débloquée');
        closeCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteCategory = async function(id) {
    try {
        await apiRequest('/categories/delete', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie supprimée');
        closeCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreCategory = async function(id) {
    try {
        await apiRequest('/categories/restore', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie restaurée');
        closeCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.confirmEraseCategory = async function() {
    if (!selectedCategory) return;
    try {
        await apiRequest('/categories/erase', {
            method: 'POST',
            body: JSON.stringify({ id: selectedCategory.id })
        });
        showToast('Catégorie supprimée définitivement');
        closeConfirmEraseCategoryModal();
        closeCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.loadTabs = async function() {
    const statusTabs = document.getElementById('statusTabs');

    try {
        const counts = await apiRequest('/categories/counts');

        let tabsHtml = '';

        if (userRole === 1 || userRole === 2) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 'all' ? 'active' : ''}" onclick="changeCategoryStatus('all')">
                    Tous(${counts.total || 0})
                </button>
            `;
        }

        tabsHtml += `
            <button class="status-tab ${currentStatus === 1 ? 'active' : ''}" onclick="changeCategoryStatus(1)">
                Actif(${counts.active || 0})
            </button>
        `;

        if (userRole === 1) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 2 ? 'active' : ''}" onclick="changeCategoryStatus(2)">
                    Bloqué(${counts.blocked || 0})
                </button>
            `;

            tabsHtml += `
                <button class="status-tab ${currentStatus === 0 ? 'active' : ''}" onclick="changeCategoryStatus(0)">
                    Corbeille(${counts.deleted || 0})
                </button>
            `;
        }

        statusTabs.innerHTML = tabsHtml;
    } catch (error) {
        console.error('Error loading counts:', error);
    }
}

window.changeCategoryStatus = function(status) {
    currentStatus = status;
    loadCategories();
    loadTabs();
}

window.getCategoryStatusBadgeClass = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'badge-active';
    if (s === 2) return 'badge-blocked';
    if (s === 0) return 'badge-deleted';
    return 'badge-active';
}

window.getCategoryStatusLabel = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'Actif';
    if (s === 2) return 'Bloqué';
    if (s === 0) return 'Supprimé';
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

document.getElementById('addCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const categoryName = document.getElementById('addCategoryName').value;
    const errorDiv = document.getElementById('addCategoryError');
    const submitBtn = document.getElementById('addCategorySubmitBtn');

    if (!categoryName) {
        errorDiv.textContent = 'Le nom de la catégorie est obligatoire.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        await apiRequest('/categories/create', {
            method: 'POST',
            body: JSON.stringify({
                category_name: categoryName
            })
        });

        showToast('Catégorie ajoutée avec succès');
        closeAddCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        let errorMessage = 'Erreur lors de la création de la catégorie';
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

document.getElementById('editCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const categoryId = document.getElementById('editCategoryId').value;
    const categoryName = document.getElementById('editCategoryName').value;
    const errorDiv = document.getElementById('editCategoryError');
    const submitBtn = document.getElementById('editCategorySubmitBtn');

    if (!categoryName) {
        errorDiv.textContent = 'Le nom de la catégorie est obligatoire.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        await apiRequest('/categories/update', {
            method: 'POST',
            body: JSON.stringify({
                category_id: categoryId,
                category_name: categoryName
            })
        });

        showToast('Catégorie modifiée avec succès');
        closeEditCategoryModal();
        loadCategories();
        loadTabs();
    } catch (error) {
        let errorMessage = 'Erreur lors de la modification de la catégorie';
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

document.getElementById('categoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeCategoryModal();
});

document.getElementById('confirmEraseCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmEraseCategoryModal();
});

document.getElementById('addCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeAddCategoryModal();
});

document.getElementById('editCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeEditCategoryModal();
});

document.addEventListener('DOMContentLoaded', function() {
    initPage();
});