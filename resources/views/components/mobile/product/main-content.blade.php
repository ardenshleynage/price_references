    <main class="main-content">
        <div class="page-header">
            <div class="page-header-left">
                <h1>Produits</h1>
                <span class="item-count" id="itemCount">0 article(s)</span>
            </div>
            <button class="add-btn" id="addProductBtn" onclick="openAddProductModal()" style="display: none;">
                <i class='bx bx-plus'></i>
            </button>
        </div>

        <div class="loading-state" id="loadingState">
            <div class="spinner-large"></div>
            <p>Chargement des produits...</p>
        </div>

        <div class="not-auth-content" id="notAuthContent" style="display: none;">
            <p>Veuillez vous connecter pour voir les produits.</p>
        </div>

        <div class="authenticated-content" id="authenticatedContent" style="display: none;">
            <div class="status-tabs" id="statusTabs"></div>
            <div class="data-list" id="dataList"></div>
            <div class="pagination-info" id="paginationInfo" style="display: none;"></div>
        </div>
    </main>
    {{ $slot }}
