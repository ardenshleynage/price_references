<!-- Search Modal -->
<div class="modal-overlay" id="searchModal">
    <div class="modal-content-mobile search-modal">
        <button class="modal-close" onclick="closeSearchModal()">
            <i class='bx bx-x'></i>
        </button>
        
        <div class="search-header">
            <h2>Recherche</h2>
        </div>
        
        <div class="search-input-container">
            <i class='bx bx-search'></i>
            <input type="text" id="searchInput" class="search-input" placeholder="Rechercher..." autocomplete="off">
        </div>
        
        <div class="search-results" id="searchResults">
            <p class="search-hint">Tapez pour rechercher...</p>
        </div>
        
        <div class="search-loading" id="searchLoading" style="display: none;">
            <div class="spinner-small"></div>
            <p>Recherche en cours...</p>
        </div>
    </div>
</div>

<!-- Search Product Detail Modal -->
<div class="modal-overlay" id="searchProductModal">
    <div class="modal-content-mobile">
        <button class="modal-close" onclick="closeSearchProductModal()">
            <i class='bx bx-x'></i>
        </button>
        
        <div class="modal-header-mobile">
            <h2>Détails produit</h2>
        </div>
        
        <div class="modal-body-mobile">
            <div class="modal-product-name" id="searchModalProductName"></div>
            <div class="modal-status-badge" id="searchModalStatusBadge"></div>
            
            <div class="modal-details">
                <div class="modal-row">
                    <span class="modal-label">Prix unitaire:</span>
                    <span class="modal-value" id="searchModalSinglePrice">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Prix détaillé:</span>
                    <span class="modal-value" id="searchModalDetailedPrice">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Catégorie:</span>
                    <span class="modal-value" id="searchModalCategoryName">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Branche:</span>
                    <span class="modal-value" id="searchModalBranchName">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Créé le:</span>
                    <span class="modal-value" id="searchModalCreatedAt">-</span>
                </div>
                <div class="modal-row">
                    <span class="modal-label">Modifié le:</span>
                    <span class="modal-value" id="searchModalUpdatedAt">-</span>
                </div>
                <div class="modal-row" id="searchPostScriptumRow" style="display: none;">
                    <span class="modal-label">Description:</span>
                    <span class="modal-value" id="searchModalPostScriptum">-</span>
                </div>
            </div>
            
            <div class="modal-actions" id="searchModalProductActions"></div>
        </div>
    </div>
</div>

<script>
    let searchTimeout = null;
    let searchResultsData = { products: [], users: [], categories: [], branches: [] };

    function openSearchModal() {
        document.getElementById('searchModal').classList.add('active');
        document.getElementById('searchInput').focus();
    }

    function closeSearchModal() {
        document.getElementById('searchModal').classList.remove('active');
        document.getElementById('searchInput').value = '';
        document.getElementById('searchResults').innerHTML = '<p class="search-hint">Tapez pour rechercher...</p>';
    }

    document.getElementById('searchInput').addEventListener('input', function(e) {
        const query = e.target.value.trim();
        
        if (searchTimeout) {
            clearTimeout(searchTimeout);
        }
        
        if (query.length < 2) {
            document.getElementById('searchResults').innerHTML = '<p class="search-hint">Tapez pour rechercher...</p>';
            return;
        }
        
        searchTimeout = setTimeout(() => performSearch(query), 300);
    });

    async function performSearch(query) {
        const searchResults = document.getElementById('searchResults');
        const searchLoading = document.getElementById('searchLoading');
        
        searchResults.style.display = 'none';
        searchLoading.style.display = 'flex';
        
        try {
            const data = await apiRequest('/search?q=' + encodeURIComponent(query));
            
            // Store results globally
            searchResultsData = data;
            
            searchLoading.style.display = 'none';
            searchResults.style.display = 'block';
            
            if (!data.products.length && !data.users?.length && !data.categories.length && !data.branches.length) {
                searchResults.innerHTML = '<p class="search-empty">Aucun résultat trouvé.</p>';
                return;
            }
            
            let html = '';
            
            if (data.users && data.users.length) {
                html += '<div class="search-group">';
                html += '<h3 class="search-group-title">Utilisateurs</h3>';
                data.users.forEach(item => {
                    html += `<div class="search-item" onclick="openUserFromSearch(${item.id})">
                        <i class='bx bxs-user'></i>
                        <div class="search-item-content">
                            <span class="search-item-name">${item.username}</span>
                            <span class="search-item-detail">${item.role === 2 ? 'Administrateur' : 'Lecteur'}</span>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </div>`;
                });
                html += '</div>';
            }
            
            if (data.products.length) {
                html += '<div class="search-group">';
                html += '<h3 class="search-group-title">Produits</h3>';
                data.products.forEach(item => {
                    html += `<div class="search-item" onclick="openProductFromSearch(${item.id})">
                        <i class='bx bxs-package'></i>
                        <div class="search-item-content">
                            <span class="search-item-name">${item.product_name}</span>
                            <span class="search-item-detail">${item.category?.category_name || '-'} | ${item.branch?.branche_name || '-'}</span>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </div>`;
                });
                html += '</div>';
            }
            
            if (data.categories.length) {
                html += '<div class="search-group">';
                html += '<h3 class="search-group-title">Catégories</h3>';
                data.categories.forEach(item => {
                    html += `<div class="search-item" onclick="openCategoryFromSearch(${item.id})">
                        <i class='bx bxs-folder'></i>
                        <div class="search-item-content">
                            <span class="search-item-name">${item.category_name}</span>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </div>`;
                });
                html += '</div>';
            }
            
            if (data.branches.length) {
                html += '<div class="search-group">';
                html += '<h3 class="search-group-title">Branches</h3>';
                data.branches.forEach(item => {
                    html += `<div class="search-item" onclick="openBranchFromSearch(${item.id})">
                        <i class='bx bxs-store'></i>
                        <div class="search-item-content">
                            <span class="search-item-name">${item.branche_name}</span>
                            <span class="search-item-detail">${item.location || '-'}</span>
                        </div>
                        <i class='bx bx-chevron-right'></i>
                    </div>`;
                });
                html += '</div>';
            }
            
            searchResults.innerHTML = html;
            
        } catch (error) {
            searchLoading.style.display = 'none';
            searchResults.style.display = 'block';
            searchResults.innerHTML = '<p class="search-empty">Erreur lors de la recherche.</p>';
        }
    }

    function openProductFromSearch(productId) {
        openSearchProductModal(productId);
    }

    async function openSearchProductModal(productId) {
        const modal = document.getElementById('searchProductModal');
        const user = getUser();
        
        try {
            const data = await apiRequest('/products?status=all');
            const product = data.data.find(p => p.id === productId);
            
            if (!product) {
                showToast('Produit non trouvé');
                return;
            }

            document.getElementById('searchModalProductName').textContent = product.product_name || '-';
            document.getElementById('searchModalSinglePrice').textContent = product.single_price ? parseFloat(product.single_price).toFixed(2) + ' €' : '-';
            document.getElementById('searchModalDetailedPrice').textContent = product.detailed_price || '-';
            document.getElementById('searchModalCategoryName').textContent = product.category?.category_name || '-';
            document.getElementById('searchModalBranchName').textContent = product.branch?.branche_name || '-';
            document.getElementById('searchModalCreatedAt').textContent = product.created_at_formatted || formatDate(product.created_at);
            document.getElementById('searchModalUpdatedAt').textContent = product.updated_at_formatted || formatDate(product.updated_at);
            
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

            const actionsDiv = document.getElementById('searchModalProductActions');
            let actionsHtml = '';
            const userRole = parseInt(user.role) || 3;
            const productStatus = parseInt(product.status);

            if (userRole === 1) {
                if (productStatus === 1) {
                    actionsHtml += `
                        <button class="btn btn-warning" onclick="blockProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-block'></i> Bloquer</button>
                        <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash'></i> Supprimer</button>
                    `;
                } else if (productStatus === 2) {
                    actionsHtml += `
                        <button class="btn btn-success" onclick="unblockProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-check-circle'></i> Débloquer</button>
                        <button class="btn btn-danger-outline" onclick="deleteProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash'></i> Supprimer</button>
                    `;
                } else if (productStatus === 0) {
                    actionsHtml += `
                        <button class="btn btn-success" onclick="restoreProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                        <button class="btn btn-danger" onclick="openConfirmEraseModalFromSearch(${product.id});"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                    `;
                }
            } else if (userRole === 2) {
                if (productStatus === 1) {
                    actionsHtml += `<button class="btn btn-danger-outline" onclick="deleteProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash'></i> Supprimer</button>`;
                } else if (productStatus === 0) {
                    actionsHtml += `<button class="btn btn-success" onclick="restoreProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash-alt'></i> Restaurer</button>`;
                }
            }

            actionsDiv.innerHTML = actionsHtml;
            closeSearchModal();
            modal.classList.add('active');
        } catch (error) {
            console.error('Error loading product:', error);
            showToast('Erreur lors du chargement du produit');
        }
    }
            } else if (productStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-success" onclick="restoreProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseModalFromSearch(${product.id});"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 2) {
            if (productStatus === 1) {
                actionsHtml += `<button class="btn btn-danger-outline" onclick="deleteProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash'></i> Supprimer</button>`;
            } else if (productStatus === 0) {
                actionsHtml += `<button class="btn btn-success" onclick="restoreProduct(${product.id}); closeSearchProductModal();"><i class='bx bxs-trash-alt'></i> Restaurer</button>`;
            }
        }

        actionsDiv.innerHTML = actionsHtml;
        closeSearchModal();
        modal.classList.add('active');
    }

    function closeSearchProductModal() {
        document.getElementById('searchProductModal').classList.remove('active');
    }

    function openConfirmEraseModalFromSearch(productId) {
        selectedProduct = { id: productId };
        document.getElementById('confirmEraseModal').classList.add('active');
    }

    document.getElementById('searchProductModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSearchProductModal();
        }
    });

    function openUserFromSearch(userId) {
        closeSearchModal();
        showToast('Utilisateur: ' + userId);
    }

    function openCategoryFromSearch(categoryId) {
        closeSearchModal();
        showToast('Catégorie: ' + categoryId);
    }

    function openBranchFromSearch(branchId) {
        closeSearchModal();
        showToast('Branche: ' + branchId);
    }

    document.getElementById('searchModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeSearchModal();
        }
    });

    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeSearchModal();
            closeSearchProductModal();
        }
    });
</script>

<style>
    .search-modal {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 95%;
        max-width: 500px;
        max-height: 90vh;
        display: flex;
        flex-direction: column;
        border-radius: 20px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
        background: var(--card-bg);
    }

    .search-modal.active {
        animation: searchModalIn 0.3s ease;
    }

    @keyframes searchModalIn {
        from {
            opacity: 0;
            transform: translate(-50%, -50%) scale(0.9);
        }
        to {
            opacity: 1;
            transform: translate(-50%, -50%) scale(1);
        }
    }

    .search-header {
        background: linear-gradient(135deg, var(--primary), var(--secondary));
        padding: 20px;
        border-radius: 20px 20px 0 0;
        text-align: center;
    }

    .search-header h2 {
        color: white;
        font-size: 18px;
        margin: 0;
    }

    .search-input-container {
        position: relative;
        padding: 16px;
        border-bottom: 1px solid var(--border-color);
    }

    .search-input-container i {
        position: absolute;
        left: 28px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 20px;
        color: var(--gray);
    }

    .search-input {
        width: 100%;
        padding: 14px 16px 14px 48px;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        font-size: 16px;
        background: var(--card-bg);
        color: var(--text-color);
    }

    .search-input:focus {
        outline: none;
        border-color: var(--primary);
    }

    .search-input::placeholder {
        color: var(--gray);
    }

    .search-results {
        flex: 1;
        overflow-y: auto;
        padding: 8px;
        background: var(--card-bg);
    }

    .search-hint,
    .search-empty {
        text-align: center;
        padding: 40px 20px;
        color: var(--gray);
        font-size: 14px;
    }

    .search-loading {
        display: flex;
        flex-direction: column;
        align-items: center;
        padding: 40px 20px;
        color: var(--gray);
        background: var(--card-bg);
    }

    .spinner-small {
        width: 32px;
        height: 32px;
        border: 3px solid var(--light);
        border-top-color: var(--primary);
        border-radius: 50%;
        animation: spin 0.8s linear infinite;
        margin-bottom: 12px;
    }

    .search-group {
        margin-bottom: 16px;
    }

    .search-group-title {
        font-size: 13px;
        font-weight: 600;
        color: var(--gray);
        padding: 8px 12px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .search-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 12px;
        border-radius: 12px;
        cursor: pointer;
        transition: background 0.2s;
    }

    .search-item:hover {
        background: var(--light);
    }

    .search-item > i:first-child {
        font-size: 24px;
        color: var(--primary);
    }

    .search-item-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        gap: 2px;
    }

    .search-item-name {
        font-size: 15px;
        font-weight: 500;
        color: var(--text-color);
    }

    .search-item-detail {
        font-size: 12px;
        color: var(--gray);
    }

    .search-item > i:last-child {
        font-size: 20px;
        color: var(--gray);
    }
</style>
