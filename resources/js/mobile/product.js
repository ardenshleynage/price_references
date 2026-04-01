let currentStatus;
let userRole = 3;
let selectedProduct = null;

window.initPage = async function() {
    const user = getUser();
    userRole = parseInt(user.role) || 3;

    if (userRole === 1 || userRole === 2) {
        currentStatus = 'all';
        document.getElementById('addProductBtn').style.display = 'flex';
    } else {
        currentStatus = 1;
    }

    loadTabs();
    loadProducts();
}

window.openAddProductModal = async function() {
    const modal = document.getElementById('addProductModal');
    const form = document.getElementById('addProductForm');
    form.reset();
    document.getElementById('addProductError').style.display = 'none';
    document.getElementById('detailedPriceContainerMobile').style.display = 'none';
    document.getElementById('toggleDetailedPriceBtn').textContent = '+ Ajouter un prix détaillé';

    document.getElementById('addBranchId').value = '';
    document.getElementById('addCategoryId').value = '';
    document.querySelector('#branchSelect .custom-select-value').textContent = '- Sélectionner une branche -';
    document.querySelector('#categorySelect .custom-select-value').textContent =
        '- Sélectionner une catégorie -';

    closeAllCustomSelects();

    try {
        const [branchesData, categoriesData] = await Promise.all([
            apiRequest('/branches?status=1'),
            apiRequest('/categories?status=1')
        ]);

        const branchOptions = document.getElementById('branchOptions');
        branchOptions.innerHTML = '';
        if (branchesData.data) {
            branchesData.data.forEach(branch => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = branch.branche_name;
                option.dataset.value = branch.id;
                option.addEventListener('click', function() {
                    selectBranch(branch.id, branch.branche_name);
                });
                branchOptions.appendChild(option);
            });
        }
        
        const categoryOptions = document.getElementById('categoryOptions');
        categoryOptions.innerHTML = '';
        if (categoriesData.data) {
            categoriesData.data.forEach(category => {
                const option = document.createElement('div');
                option.className = 'custom-select-option';
                option.textContent = category.category_name;
                option.dataset.value = category.id;
                option.addEventListener('click', function() {
                    selectCategory(category.id, category.category_name);
                });
                categoryOptions.appendChild(option);
            });
        }

        modal.classList.add('active');
    } catch (error) {
        showToast('Erreur lors du chargement des données');
    }
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

window.selectBranch = function(value, text) {
    document.getElementById('addBranchId').value = value;
    document.querySelector('#branchSelect .custom-select-value').textContent = text;
    document.getElementById('branchSelect').classList.remove('active');
}

window.selectCategory = function(value, text) {
    document.getElementById('addCategoryId').value = value;
    document.querySelector('#categorySelect .custom-select-value').textContent = text;
    document.getElementById('categorySelect').classList.remove('active');
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

window.closeAddProductModal = function() {
    document.getElementById('addProductModal').classList.remove('active');
}

window.toggleDetailedPriceMobile = function() {
    const container = document.getElementById('detailedPriceContainerMobile');
    const btn = document.getElementById('toggleDetailedPriceBtn');
    if (container.style.display === 'none') {
        container.style.display = 'block';
        btn.textContent = '- Retirer le prix détaillé';
    } else {
        container.style.display = 'none';
        document.getElementById('addDetailedPrice').value = '';
        btn.textContent = '+ Ajouter un prix détaillé';
    }
}

document.getElementById('addProductForm').addEventListener('submit', async function(e) {
    e.preventDefault();

    const productName = document.getElementById('addProductName').value;
    const singlePrice = document.getElementById('addSinglePrice').value;
    const detailedPrice = document.getElementById('addDetailedPrice').value;
    const postScriptum = document.getElementById('addPostScriptum').value;
    const branchId = document.getElementById('addBranchId').value;
    const categoryId = document.getElementById('addCategoryId').value;
    const errorDiv = document.getElementById('addProductError');
    const submitBtn = document.getElementById('addProductSubmitBtn');

    if (!productName || !singlePrice || !branchId || !categoryId) {
        errorDiv.textContent = 'Veuillez remplir tous les champs obligatoires.';
        errorDiv.style.display = 'block';
        return;
    }

    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="btn-spinner"></span>';
    errorDiv.style.display = 'none';

    try {
        const data = await apiRequest('/products/create', {
            method: 'POST',
            body: JSON.stringify({
                product_name: productName,
                single_price: singlePrice,
                detailed_price: detailedPrice,
                post_scriptum: postScriptum,
                branch_id: branchId,
                category_id: categoryId
            })
        });

        showToast('Produit ajouté avec succès');
        closeAddProductModal();
        loadProducts();
        loadTabs();
    } catch (error) {
        let errorMessage = 'Erreur lors de la création du produit';
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

document.getElementById('addProductModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeAddProductModal();
    }
});

window.loadProducts = async function() {
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
        let url = '/products?status=' + currentStatus;
        if (currentStatus === 'all') {
            url = '/products?status=all';
        }

        const data = await apiRequest(url);
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';

        if (data.data && data.data.length > 0) {
            itemCount.textContent = data.total + ' article(s)';

            dataList.innerHTML = data.data.map(product => `
                <div class="data-card" onclick="openProductModal(${product.id})" style="cursor: pointer;">
                    <div class="data-card-header">
                        <h3>${product.product_name || product.name || '-'}</h3>
                        ${userRole !== 3 ? `<span class="badge ${getStatusBadgeClass(product.status)}">
                            ${getStatusLabel(product.status)}
                        </span>` : ''}
                    </div>
                    <div class="data-card-body">
                        <div class="data-item">
                            <span class="data-label">Prix:</span>
                            <span class="data-value">${product.single_price ? parseFloat(product.single_price).toFixed(2) + ' HTG' : '-'}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Catégorie:</span>
                            <span class="data-value">${product.category?.category_name || '-'}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Branche:</span>
                            <span class="data-value">${product.branch?.branche_name || '-'}</span>
                        </div>
                        <div class="data-item">
                            <span class="data-label">Modifié:</span>
                            <span class="data-value">${formatDate(product.updated_at)}</span>
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
            dataList.innerHTML = '<p class="empty-message">Aucun produit trouvé.</p>';
            paginationInfo.style.display = 'none';
        }
    } catch (error) {
        loadingState.style.display = 'none';
        authenticatedContent.style.display = 'block';
        dataList.innerHTML = '<p class="empty-message">Erreur lors du chargement des produits.</p>';
        showToast('Erreur lors du chargement des produits');
    }
}

window.openProductModal = async function(productId) {
    const modal = document.getElementById('productModal');
    const user = getUser();

    try {
        let url = '/products?status=all';
        const data = await apiRequest(url);
        const product = data.data.find(p => p.id === productId);

        if (!product) {
            showToast('Produit non trouvé');
            return;
        }

        selectedProduct = product;

        document.getElementById('modalProductName').textContent = product.product_name || product.name || '-';
        document.getElementById('modalSinglePrice').textContent = product.single_price ? parseFloat(product
            .single_price).toFixed(2) + ' HTG' : '-';
        document.getElementById('modalDetailedPrice').textContent = product.detailed_price || '-';
        document.getElementById('modalCategoryName').textContent = product.category?.category_name || '-';
        document.getElementById('modalBranchName').textContent = product.branch?.branche_name || '-';
        document.getElementById('modalCreatedAt').textContent = formatDate(product.created_at);
        document.getElementById('modalUpdatedAt').textContent = formatDate(product.updated_at);

        const statusBadge = document.getElementById('modalStatusBadge');
        if (userRole !== 3) {
            statusBadge.className = 'modal-status-badge ' + getStatusBadgeClass(product.status);
            statusBadge.textContent = getStatusLabel(product.status);
            statusBadge.style.display = 'block';
        } else {
            statusBadge.style.display = 'none';
        }

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
                actionsHtml += `
                    <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (productStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditProductModal(${product.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreProduct(${product.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseModal(${product.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 3) {
            // Reader: no actions
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add('active');
    } catch (error) {
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
        loadProducts();
        loadTabs();
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
        loadProducts();
        loadTabs();
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
        loadProducts();
        loadTabs();
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
        loadProducts();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la restauration');
    }
}

window.openConfirmEraseModal = function(id) {
    selectedProduct = {
        id: id
    };
    document.getElementById('confirmEraseModal').classList.add('active');
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
        loadProducts();
        loadTabs();
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
        loadProducts();
        loadTabs();
    } catch (error) {
        showToast('Erreur lors de la suppression');
    }
}

document.getElementById('productModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeProductModal();
    }
});

document.getElementById('confirmEraseModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeConfirmEraseModal();
    }
});

window.loadTabs = async function() {
    const statusTabs = document.getElementById('statusTabs');
    
    if (userRole === 3) {
        statusTabs.style.display = 'none';
        return;
    }
    
    statusTabs.style.display = 'flex';

    try {
        const counts = await apiRequest('/products/counts');

        let tabsHtml = '';

        if (userRole === 1 || userRole === 2) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 'all' ? 'active' : ''}" onclick="changeStatus('all')">
                    Tous(${counts.total})
                </button>
            `;
        }

        tabsHtml += `
            <button class="status-tab ${currentStatus === 1 ? 'active' : ''}" onclick="changeStatus(1)">
                Actif(${counts.active})
            </button>
        `;

        if (userRole === 1) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 2 ? 'active' : ''}" onclick="changeStatus(2)">
                    Bloqué(${counts.blocked})
                </button>
            `;
        }

        if (userRole === 2) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 2 ? 'active' : ''}" onclick="changeStatus(2)">
                    Corbeille(${counts.blocked})
                </button>
            `;
        }

        if (userRole === 1) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 0 ? 'active' : ''}" onclick="changeStatus(0)">
                    Corbeille(${counts.deleted})
                </button>
            `;
        }

        statusTabs.innerHTML = tabsHtml;
    } catch (error) {
        console.error('Error loading counts:', error);
    }
}

window.changeStatus = function(status) {
    currentStatus = status;
    loadProducts();
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
    if (s === 0) return 'Supprimé';
    if (s === 2) {
        return userRole == 2 ? 'Supprimé' : 'Bloqué';
    }
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
});
