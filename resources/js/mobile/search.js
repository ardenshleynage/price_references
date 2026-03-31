let searchTimeout = null;
let selectedProduct = null;
window.window.userRole = 3;
let currentSearchQuery = '';

window.initPage = async function() {
    const user = getUser();
    console.log('Search page - User data:', user);
    if (!user.id || !user.token) {
        document.getElementById('notAuthContent').style.display = 'block';
        document.querySelector('.search-input-container-page').style.display = 'none';
        return;
    }
    window.window.userRole = parseInt(user.role) || 3;
    console.log('Search page - User role set to:', window.window.userRole);
}

window.goBack = function() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = MOBILE_DASHBOARD_URL;
    }
}

window.performSearch = async function(query) {
    currentSearchQuery = query;
    const searchResults = document.getElementById('searchResults');
    const searchLoading = document.getElementById('searchLoading');

    searchResults.style.display = 'none';
    searchLoading.style.display = 'flex';

    try {
        const data = await apiRequest('/search?q=' + encodeURIComponent(query));
        searchLoading.style.display = 'none';
        searchResults.style.display = 'block';

        const totalResults = (data.products?.length || 0) +
            (data.users?.length || 0) +
            (data.categories?.length || 0) +
            (data.branches?.length || 0);

        if (totalResults === 0) {
            searchResults.innerHTML = '<p class="search-empty-page">Aucun résultat trouvé.</p>';
            return;
        }

        let html = '';

        html += `<div class="search-summary">
            <span class="search-total">${totalResults} article${totalResults > 1 ? 's' : ''} trouvé${totalResults > 1 ? 's' : ''}</span>
        </div>`;

        if (data.products && data.products.length) {
            html += `<div class="search-group">`;
            html += `<div class="search-group-header" onclick="toggleSearchGroup('products')">
                <span class="search-group-title">Produits (${data.products.length})</span>
                <i class='bx bxs-chevron-down' id="products-icon"></i>
            </div>`;
            html += `<div class="search-group-list" id="products-list">`;
            data.products.forEach(item => {
                html += `<div class="search-item-page" onclick="openProductModal(${item.id})">
                    <i class='bx bxs-package'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.product_name}</span>
                        <span class="search-item-detail">${item.category?.category_name || '-'} | ${item.branch?.branche_name || '-'}</span>
                    </div>
                    <span class="badge ${getStatusBadgeClass(item.status)}">${getStatusLabel(item.status)}</span>
                </div>`;
            });
            html += `</div></div>`;
        }

        if (data.categories && data.categories.length) {
            html += `<div class="search-group">`;
            html += `<div class="search-group-header" onclick="toggleSearchGroup('categories')">
                <span class="search-group-title">Catégories (${data.categories.length})</span>
                <i class='bx bxs-chevron-down' id="categories-icon"></i>
            </div>`;
            html += `<div class="search-group-list" id="categories-list">`;
            data.categories.forEach(item => {
                html += `<div class="search-item-page" onclick="openCategoryModal(${item.id})">
                    <i class='bx bxs-folder'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.category_name}</span>
                        <span class="search-item-detail">${item.created_at_formatted || ''}</span>
                    </div>
                    <span class="badge ${getCategoryStatusBadgeClass(item.status)}">${getCategoryStatusLabel(item.status)}</span>
                </div>`;
            });
            html += `</div></div>`;
        }

        if (data.branches && data.branches.length) {
            html += `<div class="search-group">`;
            html += `<div class="search-group-header" onclick="toggleSearchGroup('branches')">
                <span class="search-group-title">Branches (${data.branches.length})</span>
                <i class='bx bxs-chevron-down' id="branches-icon"></i>
            </div>`;
            html += `<div class="search-group-list" id="branches-list">`;
            data.branches.forEach(item => {
                html += `<div class="search-item-page" onclick="openBranchModalFromSearch(${item.id})">
                    <i class='bx bxs-store'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.branche_name}</span>
                        <span class="search-item-detail">${item.created_at_formatted || ''}</span>
                    </div>
                    <span class="badge ${getBranchStatusBadgeClass(item.status)}">${getBranchStatusLabel(item.status)}</span>
                </div>`;
            });
            html += `</div></div>`;
        }

        if (data.users && data.users.length) {
            html += `<div class="search-group">`;
            html += `<div class="search-group-header" onclick="toggleSearchGroup('users')">
                <span class="search-group-title">Utilisateurs (${data.users.length})</span>
                <i class='bx bxs-chevron-down' id="users-icon"></i>
            </div>`;
            html += `<div class="search-group-list" id="users-list">`;
            data.users.forEach(item => {
                const statusClass = item.status == 1 ? 'badge-active' : (item.status == 2 ? 'badge-blocked' : 'badge-deleted');
                const statusLabel = item.status == 1 ? 'Actif' : (item.status == 2 ? 'Bloqué' : 'Supprimé');
                html += `<div class="search-item-page" onclick="openSearchUserModal(${item.id})">
                    <i class='bx bxs-user'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.username}</span>
                        <span class="search-item-detail">${item.role === 2 ? 'Administrateur' : 'Lecteur'}</span>
                    </div>
                    <span class="badge ${statusClass}">${statusLabel}</span>
                </div>`;
            });
            html += `</div></div>`;
        }

        searchResults.innerHTML = html;

    } catch (error) {
        console.error('Search error:', error);
        searchLoading.style.display = 'none';
        searchResults.style.display = 'block';
        searchResults.innerHTML = '<p class="search-empty-page">Erreur lors de la recherche.</p>';
    }
}

window.toggleSearchGroup = function(group) {
    const list = document.getElementById(group + '-list');
    const icon = document.getElementById(group + '-icon');

    if (list.style.display === 'none') {
        list.style.display = 'block';
        if (icon) icon.style.transform = 'rotate(180deg)';
    } else {
        list.style.display = 'none';
        if (icon) icon.style.transform = 'rotate(0deg)';
    }
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

window.formatDate = function(dateString) {
    if (!dateString) return '-';
    const date = new Date(dateString);
    return date.toLocaleDateString('fr-FR', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
    });
}

window.openProductModal = async function(productId) {
    const modal = document.getElementById('searchProductModal');
    const user = getUser();

    try {
        const data = await apiRequest('/products?status=all');
        const product = data.data.find(p => p.id === productId);

        if (!product) {
            showToast('Produit non trouvé');
            return;
        }

        selectedProduct = product;

        document.getElementById('searchModalProductName').textContent = product.product_name || '-';
        document.getElementById('searchModalSinglePrice').textContent = product.single_price ? parseFloat(product
            .single_price).toFixed(2) + ' HTG' : '-';
        document.getElementById('searchModalDetailedPrice').textContent = product.detailed_price || '-';
        document.getElementById('searchModalCategoryName').textContent = product.category?.category_name || '-';
        document.getElementById('searchModalProductBranchName').textContent = product.branch?.branche_name || '-';
        document.getElementById('searchModalCreatedAt').textContent = product.created_at_formatted || formatDate(
            product.created_at);
        document.getElementById('searchModalUpdatedAt').textContent = product.updated_at_formatted || formatDate(
            product.updated_at);

        const statusBadge = document.getElementById('searchModalStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getStatusBadgeClass(product.status);
        statusBadge.textContent = getStatusLabel(product.status);

        const postScriptumRow = document.getElementById('searchPostScriptumRow');
        if (product.post_scriptum) {
            document.getElementById('searchModalPostScriptum').textContent = product.post_scriptum;
            postScriptumRow.style.display = 'flex';
        } else {
            postScriptumRow.style.display = 'none';
        }

        const actionsDiv = document.getElementById('searchModalActions');
        let actionsHtml = '';
        const productStatus = parseInt(product.status);
        
        console.log('Product modal - window.userRole:', window.userRole, 'productStatus:', productStatus);

        if (window.userRole === 1) {
            if (productStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockProduct(${product.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (productStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockProduct(${product.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (productStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreProduct(${product.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseModal(${product.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (window.userRole === 2) {
            if (productStatus === 1) {
                actionsHtml +=
                    `<button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>`;
            } else if (productStatus === 0) {
                actionsHtml +=
                    `<button class="btn btn-success" onclick="restoreProduct(${product.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>`;
            }
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        console.error('Error:', error);
        showToast('Erreur lors du chargement du produit');
    }
}

window.closeSearchProductModal = function() {
    document.getElementById('searchProductModal').classList.remove('active');
    selectedProduct = null;
}

window.closeProductModal = window.closeSearchProductModal;

window.closeConfirmEraseModal = function() {
    document.getElementById('confirmEraseModal').classList.remove('active');
}

window.openConfirmEraseModal = function(id) {
    selectedProduct = {
        id: id
    };
    document.getElementById('confirmEraseModal').classList.add('active');
}

window.blockProduct = async function(id) {
    try {
        await apiRequest('/products/block', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Produit bloqué');
        closeProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockProduct = async function(id) {
    try {
        await apiRequest('/products/unblock', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Produit débloqué');
        closeProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteProduct = async function(id) {
    try {
        await apiRequest('/products/delete', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Produit supprimé');
        closeProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreProduct = async function(id) {
    try {
        await apiRequest('/products/restore', {
            method: 'POST',
            body: JSON.stringify({
                id: id
            })
        });
        showToast('Produit restauré');
        closeProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.confirmEraseProduct = async function() {
    if (!selectedProduct) return;
    try {
        await apiRequest('/products/erase', {
            method: 'POST',
            body: JSON.stringify({
                id: selectedProduct.id
            })
        });
        showToast('Produit supprimé définitivement');
        closeConfirmEraseModal();
        closeProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.openSearchEditProductModal = async function(id) {
    const modal = document.getElementById('searchEditProductModal');
    const form = document.getElementById('searchEditProductForm');
    form.reset();
    document.getElementById('searchEditProductError').style.display = 'none';
    document.getElementById('searchEditDetailedPriceContainer').style.display = 'none';
    document.getElementById('searchToggleEditDetailedPriceBtn').textContent = '+ Ajouter un prix détaillé';

    closeSearchProductModal();
    closeAllCustomSelects();

    try {
        const data = await apiRequest('/products?status=all');
        const product = data.data.find(p => p.id === id);

        if (!product) {
            showToast('Produit non trouvé');
            return;
        }

        document.getElementById('searchEditProductId').value = product.id;
        document.getElementById('searchEditProductName').value = product.product_name || '';
        document.getElementById('searchEditSinglePrice').value = product.single_price || '';
        document.getElementById('searchEditDetailedPrice').value = product.detailed_price || '';
        document.getElementById('searchEditPostScriptum').value = product.post_scriptum || '';

        if (product.detailed_price) {
            document.getElementById('searchEditDetailedPriceContainer').style.display = 'block';
            document.getElementById('searchToggleEditDetailedPriceBtn').textContent = '- Retirer le prix détaillé';
        }

        const [branchesData, categoriesData] = await Promise.all([
            apiRequest('/branches?status=1'),
            apiRequest('/categories?status=1')
        ]);

        const editBranchOptions = document.getElementById('searchEditBranchOptions');
        editBranchOptions.innerHTML = '';
        if (branchesData.data) {
            branchesData.data.forEach(branch => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = branch.branche_name;
                option.dataset.value = branch.id;
                option.addEventListener('click', function() {
                    selectSearchEditBranch(branch.id, branch.branche_name);
                });
                editBranchOptions.appendChild(option);
            });
        }

        const editCategoryOptions = document.getElementById('searchEditCategoryOptions');
        editCategoryOptions.innerHTML = '';
        if (categoriesData.data) {
            categoriesData.data.forEach(category => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = category.category_name;
                option.dataset.value = category.id;
                option.addEventListener('click', function() {
                    selectSearchEditCategory(category.id, category.category_name);
                });
                editCategoryOptions.appendChild(option);
            });
        }

        if (product.branch) {
            document.getElementById('searchEditBranchId').value = product.branch.id;
            document.getElementById('searchEditBranchValue').textContent = product.branch.branche_name;
        }

        if (product.category) {
            document.getElementById('searchEditCategoryId').value = product.category.id;
            document.getElementById('searchEditCategoryValue').textContent = product.category.category_name;
        }

        modal.classList.add('active');
    } catch (error) {
        console.error('Error:', error);
        showToast('Erreur lors du chargement du produit');
    }
}

window.openEditProductModal = window.openSearchEditProductModal;

window.closeSearchEditProductModal = function() {
    document.getElementById('searchEditProductModal').classList.remove('active');
}

window.closeEditProductModal = window.closeSearchEditProductModal;

window.selectSearchEditBranch = function(value, text) {
    document.getElementById('searchEditBranchId').value = value;
    document.getElementById('searchEditBranchValue').textContent = text;
    document.getElementById('searchEditBranchSelect').classList.remove('active');
}

window.selectEditBranch = window.selectSearchEditBranch;

window.selectSearchEditCategory = function(value, text) {
    document.getElementById('searchEditCategoryId').value = value;
    document.getElementById('searchEditCategoryValue').textContent = text;
    document.getElementById('searchEditCategorySelect').classList.remove('active');
}

window.selectEditCategory = window.selectSearchEditCategory;

window.toggleSearchEditDetailedPrice = function() {
    const container = document.getElementById('searchEditDetailedPriceContainer');
    const btn = document.getElementById('searchToggleEditDetailedPriceBtn');
    if (container.style.display === 'none') {
        container.style.display = 'block';
        btn.textContent = '- Retirer le prix détaillé';
    } else {
        container.style.display = 'none';
        document.getElementById('searchEditDetailedPrice').value = '';
        btn.textContent = '+ Ajouter un prix détaillé';
    }
}

window.toggleEditDetailedPrice = window.toggleSearchEditDetailedPrice;

window.closeAllCustomSelects = function() {
    document.querySelectorAll('.custom-select').forEach(select => {
        select.classList.remove('active');
    });
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

document.getElementById('searchInput').addEventListener('input', function(e) {
    const query = e.target.value.trim();

    if (searchTimeout) {
        clearTimeout(searchTimeout);
    }

    if (query.length === 0) {
        document.getElementById('searchResults').innerHTML =
            '<p class="search-hint-page">Tapez pour rechercher...</p>';
        return;
    }

    searchTimeout = setTimeout(() => performSearch(query), 300);
});

document.getElementById('searchEditProductForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const productId = document.getElementById('searchEditProductId').value;
    const productName = document.getElementById('searchEditProductName').value;
    const singlePrice = document.getElementById('searchEditSinglePrice').value;
    const detailedPrice = document.getElementById('searchEditDetailedPrice').value;
    const postScriptum = document.getElementById('searchEditPostScriptum').value;
    const branchId = document.getElementById('searchEditBranchId').value;
    const categoryId = document.getElementById('searchEditCategoryId').value;
    const errorDiv = document.getElementById('searchEditProductError');
    const submitBtn = document.getElementById('searchEditProductSubmitBtn');

    if (!productName || !singlePrice || !branchId || !categoryId) {
        errorDiv.textContent = 'Veuillez remplir tous les champs obligatoires.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        const data = await apiRequest('/products/update', {
            method: 'POST',
            body: JSON.stringify({
                prod_id: productId,
                product_name: productName,
                single_price: singlePrice,
                detailed_price: detailedPrice,
                post_scriptum: postScriptum,
                branch_id: branchId,
                category_id: categoryId
            })
        });

        showToast('Produit modifié avec succès');
        closeSearchEditProductModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        let errorMessage = 'Erreur lors de la modification du produit';
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

document.getElementById('searchProductModal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchProductModal();
});

document.getElementById('confirmEraseModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmEraseModal();
});

document.getElementById('searchEditProductModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditProductModal();
    }
});

// ==================== CATEGORY FUNCTIONS ====================

let selectedSearchCategory = null;

window.openCategoryModal = async function(categoryId) {
    const modal = document.getElementById('searchCategoryModal');

    try {
        const data = await apiRequest('/categories?status=all');
        const category = data.data.find(c => c.id === categoryId);

        if (!category) {
            showToast('Catégorie non trouvée');
            return;
        }

        selectedSearchCategory = category;

        document.getElementById('searchModalCategoryName').textContent = category.category_name || '-';

        const statusBadge = document.getElementById('searchModalCategoryStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getCategoryStatusBadgeClass(category.status);
        statusBadge.textContent = getCategoryStatusLabel(category.status);

        document.getElementById('searchModalCategoryCreatedAt').textContent = category.created_at_formatted || formatDate(category.created_at);
        document.getElementById('searchModalCategoryUpdatedAt').textContent = category.updated_at_formatted || formatDate(category.updated_at);

        const actionsDiv = document.getElementById('searchModalCategoryActions');
        let actionsHtml = '';
        const categoryStatus = parseInt(category.status);
        
        console.log('Category modal - window.userRole:', window.userRole, 'categoryStatus:', categoryStatus);

        if (window.userRole === 1) {
            if (categoryStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockCategoryFromSearch(${category.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategoryFromSearch(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockCategoryFromSearch(${category.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategoryFromSearch(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategoryFromSearch(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseCategoryModal(${category.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (window.userRole === 2) {
            if (categoryStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-danger-outline" onclick="deleteCategoryFromSearch(${category.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (categoryStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategoryFromSearch(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            } else if (categoryStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditCategoryModal(${category.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreCategoryFromSearch(${category.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            }
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la catégorie');
    }
}

window.closeSearchCategoryModal = function() {
    document.getElementById('searchCategoryModal').classList.remove('active');
    selectedSearchCategory = null;
}

window.closeCategoryModal = window.closeSearchCategoryModal;

window.closeConfirmEraseCategoryModal = function() {
    document.getElementById('confirmEraseCategoryModal').classList.remove('active');
}

window.openConfirmEraseCategoryModal = function(id) {
    selectedSearchCategory = { id: id };
    document.getElementById('confirmEraseCategoryModal').classList.add('active');
}

window.openSearchEditCategoryModal = async function(id) {
    const modal = document.getElementById('searchEditCategoryModal');
    const form = document.getElementById('searchEditCategoryForm');
    form.reset();
    document.getElementById('searchEditCategoryError').style.display = 'none';

    closeSearchCategoryModal();

    try {
        const data = await apiRequest('/categories?status=all');
        const category = data.data.find(c => c.id === id);

        if (!category) {
            showToast('Catégorie non trouvée');
            return;
        }

        document.getElementById('searchEditCategoryId').value = category.id;
        document.getElementById('searchEditCategoryName').value = category.category_name || '';

        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la catégorie');
    }
}

window.openEditCategoryModalFromSearch = window.openSearchEditCategoryModal;

window.closeSearchEditCategoryModal = function() {
    document.getElementById('searchEditCategoryModal').classList.remove('active');
}

window.closeEditCategoryModal = window.closeSearchEditCategoryModal;

window.blockCategoryFromSearch = async function(id) {
    try {
        await apiRequest('/categories/block', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie bloquée');
        closeCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockCategoryFromSearch = async function(id) {
    try {
        await apiRequest('/categories/unblock', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie débloquée');
        closeCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteCategoryFromSearch = async function(id) {
    try {
        await apiRequest('/categories/delete', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie supprimée');
        closeCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreCategoryFromSearch = async function(id) {
    try {
        await apiRequest('/categories/restore', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Catégorie restaurée');
        closeCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.confirmEraseCategoryFromSearch = async function() {
    if (!selectedSearchCategory) return;
    try {
        await apiRequest('/categories/erase', {
            method: 'POST',
            body: JSON.stringify({ id: selectedSearchCategory.id })
        });
        showToast('Catégorie supprimée définitivement');
        closeConfirmEraseCategoryModal();
        closeCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
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

let selectedSearchUser = null;

window.openSearchUserModal = async function(userId) {
    const modal = document.getElementById('searchUserModal');

    try {
        const data = await apiRequest('/users?status=all');
        const user = data.data.find(u => u.id === userId);

        if (!user) {
            showToast('Utilisateur non trouvé');
            return;
        }

        selectedSearchUser = user;

        document.getElementById('searchModalUserUsername').textContent = user.username || '-';

        const statusBadge = document.getElementById('searchModalUserStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getUserStatusBadgeClass(user.status);
        statusBadge.textContent = getUserStatusLabel(user.status);

        document.getElementById('searchModalUserEmail').textContent = user.email || '-';
        document.getElementById('searchModalUserRole').textContent = getUserRoleLabel(user.role);
        document.getElementById('searchModalUserLastConnection').textContent = formatDate(user.last_time_connect);
        document.getElementById('searchModalUserCreatedAt').textContent = formatDate(user.created_at);
        document.getElementById('searchModalUserUpdatedAt').textContent = formatDate(user.updated_at);

        const actionsDiv = document.getElementById('searchModalUserActions');
        let actionsHtml = `
            <button class="btn btn-primary" onclick="openEditUserModalFromSearch()"><i class='bx bxs-edit'></i> Modifier</button>
        `;
        const userStatus = parseInt(user.status);

        if (userStatus === 1) {
            actionsHtml += `
                <button class="btn btn-warning" onclick="blockUserFromSearch(${user.id})"><i class='bx bxs-block'></i> Bloquer</button>
                <button class="btn btn-danger-outline" onclick="deleteUserFromSearch(${user.id})"><i class='bx bxs-trash'></i> Supprimer</button>
            `;
        } else if (userStatus === 2) {
            actionsHtml += `
                <button class="btn btn-success" onclick="unblockUserFromSearch(${user.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                <button class="btn btn-danger-outline" onclick="deleteUserFromSearch(${user.id})"><i class='bx bxs-trash'></i> Supprimer</button>
            `;
        } else if (userStatus === 0) {
            actionsHtml += `
                <button class="btn btn-success" onclick="restoreUserFromSearch(${user.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                <button class="btn btn-danger" onclick="openConfirmEraseUserModal(${user.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
            `;
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de l\'utilisateur');
    }
}

window.closeSearchUserModal = function() {
    document.getElementById('searchUserModal').classList.remove('active');
    selectedSearchUser = null;
}

window.getUserStatusBadgeClass = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'badge-active';
    if (s === 2) return 'badge-blocked';
    if (s === 0) return 'badge-deleted';
    return 'badge-active';
}

window.getUserStatusLabel = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'Actif';
    if (s === 2) return 'Bloqué';
    if (s === 0) return 'Supprimé';
    return 'Inconnu';
}

window.getUserRoleLabel = function(role) {
    const r = parseInt(role);
    if (r === 1) return 'Super Admin';
    if (r === 2) return 'Administrateur';
    if (r === 3) return 'Lecteur';
    return 'Inconnu';
}

window.blockUserFromSearch = async function(id) {
    try {
        await apiRequest('/users/block', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Utilisateur bloqué');
        closeSearchUserModal();
        if (currentSearchQuery) {
            performSearch(currentSearchQuery);
        }
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockUserFromSearch = async function(id) {
    try {
        await apiRequest('/users/unblock', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Utilisateur débloqué');
        closeSearchUserModal();
        if (currentSearchQuery) {
            performSearch(currentSearchQuery);
        }
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteUserFromSearch = async function(id) {
    try {
        await apiRequest('/users/delete', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Utilisateur supprimé');
        closeSearchUserModal();
        if (currentSearchQuery) {
            performSearch(currentSearchQuery);
        }
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreUserFromSearch = async function(id) {
    try {
        await apiRequest('/users/restore', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Utilisateur restauré');
        closeSearchUserModal();
        if (currentSearchQuery) {
            performSearch(currentSearchQuery);
        }
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

document.getElementById('searchUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeSearchUserModal();
    }
});

window.openEditUserModalFromSearch = async function() {
    if (!selectedSearchUser || !selectedSearchUser.id) {
        showToast('Utilisateur non sélectionné');
        return;
    }
    
    const userIdToEdit = selectedSearchUser.id;
    
    const editUserModal = document.getElementById('editUserModal');
    const editUserForm = document.getElementById('editUserForm');
    if (!editUserModal || !editUserForm) {
        showToast('Modal de modification non disponible');
        return;
    }
    
    closeSearchUserModal();
    
    editUserForm.reset();
    const errorEl = document.getElementById('editUserError');
    if (errorEl) errorEl.style.display = 'none';
    const roleSelectValue = document.querySelector('#editRoleSelect .custom-select-value');
    if (roleSelectValue) roleSelectValue.textContent = '- Sélectionner un rôle -';
    closeAllCustomSelects();

    try {
        const data = await apiRequest('/users?status=all');
        const user = data.data.find(u => u.id === userIdToEdit);

        if (!user) {
            showToast('Utilisateur non trouvé');
            return;
        }

        document.getElementById('editUserId').value = user.id;
        document.getElementById('editUsername').value = user.username || '';
        document.getElementById('editEmail').value = user.email || '';

        if (user.role) {
            document.getElementById('editRole').value = user.role;
            document.getElementById('editRoleValue').textContent = getUserRoleLabel(user.role);
            document.querySelector('#editRoleSelect .custom-select-value').textContent = getUserRoleLabel(user.role);
        }

        editUserModal.classList.add('active');
    } catch (error) {
        console.error('Error:', error);
        showToast('Erreur lors du chargement de l\'utilisateur');
    }
}

window.openConfirmEraseUserModal = function(id) {
    selectedSearchUser = { id: id };
    document.getElementById('confirmEraseUserModal').classList.add('active');
}

window.closeConfirmEraseUserModal = function() {
    document.getElementById('confirmEraseUserModal').classList.remove('active');
}

window.confirmEraseUserFromSearch = async function() {
    if (!selectedSearchUser) return;

    try {
        await apiRequest('/users/erase', {
            method: 'POST',
            body: JSON.stringify({ id: selectedSearchUser.id })
        });
        showToast('Utilisateur supprimé définitivement');
        closeConfirmEraseUserModal();
        closeSearchUserModal();
        if (currentSearchQuery) {
            performSearch(currentSearchQuery);
        }
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

document.getElementById('confirmEraseUserModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmEraseUserModal();
    }
});

document.getElementById('searchEditCategoryForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const categoryId = document.getElementById('searchEditCategoryId').value;
    const categoryName = document.getElementById('searchEditCategoryName').value;
    const errorDiv = document.getElementById('searchEditCategoryError');
    const submitBtn = document.getElementById('searchEditCategorySubmitBtn');

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
        closeSearchEditCategoryModal();
        performSearch(document.getElementById('searchInput').value.trim());
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

document.getElementById('searchCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchCategoryModal();
});

document.getElementById('confirmEraseCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmEraseCategoryModal();
});

document.getElementById('searchEditCategoryModal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchEditCategoryModal();
});

// ==================== BRANCH FUNCTIONS ====================

let selectedSearchBranch = null;

window.openBranchModalFromSearch = async function(branchId) {
    const modal = document.getElementById('searchBranchModal');

    try {
        const data = await apiRequest('/branches?status=all');
        const branch = data.data.find(b => b.id === branchId);

        if (!branch) {
            showToast('Branche non trouvée');
            return;
        }

        selectedSearchBranch = branch;

        console.log('Branch data:', branch);
        document.getElementById('searchModalBranchName').textContent = branch.branche_name || '-';

        const statusBadge = document.getElementById('searchModalBranchStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getBranchStatusBadgeClass(branch.status);
        statusBadge.textContent = getBranchStatusLabel(branch.status);

        document.getElementById('searchModalBranchCreatedAt').textContent = branch.created_at_formatted || formatDate(branch.created_at);
        document.getElementById('searchModalBranchUpdatedAt').textContent = branch.updated_at_formatted || formatDate(branch.updated_at);

        const actionsDiv = document.getElementById('searchModalBranchActions');
        let actionsHtml = '';
        const branchStatus = parseInt(branch.status);
        
        console.log('Branch modal - window.userRole:', window.userRole, 'branchStatus:', branchStatus);

        if (window.userRole === 1) {
            if (branchStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockBranchFromSearch(${branch.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranchFromSearch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockBranchFromSearch(${branch.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranchFromSearch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreBranchFromSearch(${branch.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseBranchModal(${branch.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (window.userRole === 2) {
            if (branchStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranchFromSearch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreBranchFromSearch(${branch.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            } else if (branchStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openSearchEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreBranchFromSearch(${branch.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                `;
            }
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la branche');
    }
}

window.closeSearchBranchModal = function() {
    document.getElementById('searchBranchModal').classList.remove('active');
    selectedSearchBranch = null;
}

window.closeBranchModal = window.closeSearchBranchModal;

window.closeConfirmEraseBranchModal = function() {
    document.getElementById('confirmEraseBranchModal').classList.remove('active');
}

window.openConfirmEraseBranchModal = function(id) {
    selectedSearchBranch = { id: id };
    document.getElementById('confirmEraseBranchModal').classList.add('active');
}

window.openSearchEditBranchModal = async function(id) {
    const modal = document.getElementById('searchEditBranchModal');
    const form = document.getElementById('searchEditBranchForm');
    form.reset();
    document.getElementById('searchEditBranchError').style.display = 'none';

    closeSearchBranchModal();

    try {
        const data = await apiRequest('/branches?status=all');
        const branch = data.data.find(b => b.id === id);

        if (!branch) {
            showToast('Branche non trouvée');
            return;
        }

        document.getElementById('searchEditBranchId').value = branch.id;
        document.getElementById('searchEditBranchName').value = branch.branche_name || '';

        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement de la branche');
    }
}

window.openEditBranchModalFromSearch = window.openSearchEditBranchModal;

window.closeSearchEditBranchModal = function() {
    document.getElementById('searchEditBranchModal').classList.remove('active');
}

window.closeEditBranchModal = window.closeSearchEditBranchModal;

window.blockBranchFromSearch = async function(id) {
    try {
        await apiRequest('/branches/block', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Branche bloquée');
        closeBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du blocage');
    }
}

window.unblockBranchFromSearch = async function(id) {
    try {
        await apiRequest('/branches/unblock', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Branche débloquée');
        closeBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors du déblocage');
    }
}

window.deleteBranchFromSearch = async function(id) {
    try {
        await apiRequest('/branches/delete', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Branche supprimée');
        closeBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.restoreBranchFromSearch = async function(id) {
    try {
        await apiRequest('/branches/restore', {
            method: 'POST',
            body: JSON.stringify({ id: id })
        });
        showToast('Branche restaurée');
        closeBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.confirmEraseBranchFromSearch = async function() {
    if (!selectedSearchBranch) return;
    try {
        await apiRequest('/branches/erase', {
            method: 'POST',
            body: JSON.stringify({ id: selectedSearchBranch.id })
        });
        showToast('Branche supprimée définitivement');
        closeConfirmEraseBranchModal();
        closeBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

window.getBranchStatusBadgeClass = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'badge-active';
    if (s === 2) return 'badge-blocked';
    if (s === 0) return 'badge-deleted';
    return 'badge-active';
}

window.getBranchStatusLabel = function(status) {
    const s = parseInt(status);
    if (s === 1) return 'Actif';
    if (s === 2) return 'Bloqué';
    if (s === 0) return 'Supprimé';
    return 'Inconnu';
}

document.getElementById('searchEditBranchForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const branchId = document.getElementById('searchEditBranchId').value;
    const branchName = document.getElementById('searchEditBranchName').value;
    const errorDiv = document.getElementById('searchEditBranchError');
    const submitBtn = document.getElementById('searchEditBranchSubmitBtn');

    if (!branchName) {
        errorDiv.textContent = 'Le nom de la branche est obligatoire.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        await apiRequest('/branches/update', {
            method: 'POST',
            body: JSON.stringify({
                branch_id: branchId,
                branche_name: branchName
            })
        });

        showToast('Branche modifiée avec succès');
        closeSearchEditBranchModal();
        performSearch(document.getElementById('searchInput').value.trim());
    } catch (error) {
        let errorMessage = 'Erreur lors de la modification de la branche';
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

document.getElementById('searchBranchModal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchBranchModal();
});

document.getElementById('confirmEraseBranchModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmEraseBranchModal();
});

document.getElementById('searchEditBranchModal').addEventListener('click', function(e) {
    if (e.target === this) closeSearchEditBranchModal();
});

document.addEventListener('DOMContentLoaded', function() {
    initPage();
});
