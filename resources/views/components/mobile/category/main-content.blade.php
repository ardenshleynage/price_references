<main class="main-content">
    <div class="page-header">
        <div class="page-header-left">
            <h1>Catégories</h1>
            <span class="item-count" id="itemCount">0 catégorie(s)</span>
        </div>
        <button class="add-btn" id="addCategoryBtn" onclick="openAddCategoryModal()" style="display: none;">
            <i class='bx bx-plus'></i>
        </button>
    </div>

    <div class="loading-state" id="loadingState">
        <div class="spinner-large"></div>
        <p>Chargement des catégories...</p>
    </div>

    <div class="not-auth-content" id="notAuthContent" style="display: none;">
        <p>Veuillez vous connecter pour voir les catégories.</p>
    </div>

    <div class="authenticated-content" id="authenticatedContent" style="display: none;">
        <div class="status-tabs" id="statusTabs"></div>
        <div class="data-list" id="dataList"></div>
        <div class="pagination-info" id="paginationInfo" style="display: none;"></div>
    </div>
</main>
{{ $slot }}
