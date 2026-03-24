let searchTimeout = null;
let selectedProduct = null;
let userRole = 3;

window.initPage = async function() {
    const user = getUser();
    if (!user.id || !user.token) {
        document.getElementById('notAuthContent').style.display = 'block';
        document.querySelector('.search-input-container-page').style.display = 'none';
        return;
    }
    userRole = parseInt(user.role) || 3;
}

window.goBack = function() {
    if (document.referrer && document.referrer !== window.location.href) {
        window.location.href = document.referrer;
    } else {
        window.location.href = MOBILE_DASHBOARD_URL;
    }
}

window.performSearch = async function(query) {
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
                html += `<div class="search-item-page">
                    <i class='bx bxs-folder'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.category_name}</span>
                    </div>
                    <i class='bx bx-chevron-right'></i>
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
                html += `<div class="search-item-page">
                    <i class='bx bxs-store'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.branche_name}</span>
                        <span class="search-item-detail">${item.location || '-'}</span>
                    </div>
                    <i class='bx bx-chevron-right'></i>
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
                html += `<div class="search-item-page">
                    <i class='bx bxs-user'></i>
                    <div class="search-item-content">
                        <span class="search-item-name">${item.username}</span>
                        <span class="search-item-detail">${item.role === 2 ? 'Administrateur' : 'Lecteur'}</span>
                    </div>
                    <i class='bx bx-chevron-right'></i>
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
    const modal = document.getElementById('productModal');
    const user = getUser();

    try {
        const data = await apiRequest('/products?status=all');
        const product = data.data.find(p => p.id === productId);

        if (!product) {
            showToast('Produit non trouvé');
            return;
        }

        selectedProduct = product;

        document.getElementById('modalProductName').textContent = product.product_name || '-';
        document.getElementById('modalSinglePrice').textContent = product.single_price ? parseFloat(product
            .single_price).toFixed(2) + ' HTG' : '-';
        document.getElementById('modalDetailedPrice').textContent = product.detailed_price || '-';
        document.getElementById('modalCategoryName').textContent = product.category?.category_name || '-';
        document.getElementById('modalBranchName').textContent = product.branch?.branche_name || '-';
        document.getElementById('modalCreatedAt').textContent = product.created_at_formatted || formatDate(
            product.created_at);
        document.getElementById('modalUpdatedAt').textContent = product.updated_at_formatted || formatDate(
            product.updated_at);

        const statusBadge = document.getElementById('modalStatusBadge');
        statusBadge.className = 'modal-status-badge ' + getStatusBadgeClass(product.status);
        statusBadge.textContent = getStatusLabel(product.status);

        const postScriptumRow = document.getElementById('postScriptumRow');
        if (product.post_scriptum) {
            document.getElementById('modalPostScriptum').textContent = product.post_scriptum;
            postScriptumRow.style.display = 'flex';
        } else {
            postScriptumRow.style.display = 'none';
        }

        const actionsDiv = document.getElementById('modalActions');
        let actionsHtml = '';
        const productStatus = parseInt(product.status);

        if (userRole === 1) {
            if (productStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockProduct(${product.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (productStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockProduct(${product.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (productStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreProduct(${product.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseModal(${product.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 2) {
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

window.closeProductModal = function() {
    document.getElementById('productModal').classList.remove('active');
    selectedProduct = null;
}

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

window.openEditProductModal = async function(id) {
    const modal = document.getElementById('editProductModal');
    const form = document.getElementById('editProductForm');
    form.reset();
    document.getElementById('editProductError').style.display = 'none';
    document.getElementById('editDetailedPriceContainer').style.display = 'none';
    document.getElementById('toggleEditDetailedPriceBtn').textContent = '+ Ajouter un prix détaillé';

    closeProductModal();
    closeAllCustomSelects();

    try {
        const data = await apiRequest('/products?status=all');
        const product = data.data.find(p => p.id === id);

        if (!product) {
            showToast('Produit non trouvé');
            return;
        }

        document.getElementById('editProductId').value = product.id;
        document.getElementById('editProductName').value = product.product_name || '';
        document.getElementById('editSinglePrice').value = product.single_price || '';
        document.getElementById('editDetailedPrice').value = product.detailed_price || '';
        document.getElementById('editPostScriptum').value = product.post_scriptum || '';

        if (product.detailed_price) {
            document.getElementById('editDetailedPriceContainer').style.display = 'block';
            document.getElementById('toggleEditDetailedPriceBtn').textContent = '- Retirer le prix détaillé';
        }

        const [branchesData, categoriesData] = await Promise.all([
            apiRequest('/branches?status=1'),
            apiRequest('/categories?status=1')
        ]);

        const editBranchOptions = document.getElementById('editBranchOptions');
        editBranchOptions.innerHTML = '';
        if (branchesData.data) {
            branchesData.data.forEach(branch => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = branch.branche_name;
                option.dataset.value = branch.id;
                option.addEventListener('click', function() {
                    selectEditBranch(branch.id, branch.branche_name);
                });
                editBranchOptions.appendChild(option);
            });
        }

        const editCategoryOptions = document.getElementById('editCategoryOptions');
        editCategoryOptions.innerHTML = '';
        if (categoriesData.data) {
            categoriesData.data.forEach(category => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = category.category_name;
                option.dataset.value = category.id;
                option.addEventListener('click', function() {
                    selectEditCategory(category.id, category.category_name);
                });
                editCategoryOptions.appendChild(option);
            });
        }

        if (product.branch) {
            document.getElementById('editBranchId').value = product.branch.id;
            document.getElementById('editBranchValue').textContent = product.branch.branche_name;
        }

        if (product.category) {
            document.getElementById('editCategoryId').value = product.category.id;
            document.getElementById('editCategoryValue').textContent = product.category.category_name;
        }

        modal.classList.add('active');
    } catch (error) {
        console.error('Error:', error);
        showToast('Erreur lors du chargement du produit');
    }
}

window.closeEditProductModal = function() {
    document.getElementById('editProductModal').classList.remove('active');
}

window.selectEditBranch = function(value, text) {
    document.getElementById('editBranchId').value = value;
    document.getElementById('editBranchValue').textContent = text;
    document.getElementById('editBranchSelect').classList.remove('active');
}

window.selectEditCategory = function(value, text) {
    document.getElementById('editCategoryId').value = value;
    document.getElementById('editCategoryValue').textContent = text;
    document.getElementById('editCategorySelect').classList.remove('active');
}

window.toggleEditDetailedPrice = function() {
    const container = document.getElementById('editDetailedPriceContainer');
    const btn = document.getElementById('toggleEditDetailedPriceBtn');
    if (container.style.display === 'none') {
        container.style.display = 'block';
        btn.textContent = '- Retirer le prix détaillé';
    } else {
        container.style.display = 'none';
        document.getElementById('editDetailedPrice').value = '';
        btn.textContent = '+ Ajouter un prix détaillé';
    }
}

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

document.getElementById('editProductForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const productId = document.getElementById('editProductId').value;
    const productName = document.getElementById('editProductName').value;
    const singlePrice = document.getElementById('editSinglePrice').value;
    const detailedPrice = document.getElementById('editDetailedPrice').value;
    const postScriptum = document.getElementById('editPostScriptum').value;
    const branchId = document.getElementById('editBranchId').value;
    const categoryId = document.getElementById('editCategoryId').value;
    const errorDiv = document.getElementById('editProductError');
    const submitBtn = document.getElementById('editProductSubmitBtn');

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
        closeEditProductModal();
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

document.getElementById('editProductModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeEditProductModal();
    }
});

document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) closeProductModal();
});

document.getElementById('confirmEraseModal').addEventListener('click', function(e) {
    if (e.target === this) closeConfirmEraseModal();
});

document.addEventListener('DOMContentLoaded', function() {
    initPage();
});
