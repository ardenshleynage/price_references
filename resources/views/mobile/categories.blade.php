<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <x-mobile.navbar />

    <main class="main-content">
        <div class="page-header">
            <h1>Catégories</h1>
            <span class="item-count" id="itemCount">0 catégorie(s)</span>
        </div>

        <div class="loading-state" id="loadingState">
            <div class="spinner-large"></div>
            <p>Chargement des catégories...</p>
        </div>

        <div class="not-auth-content" id="notAuthContent" style="display: none;">
            <p>Veuillez vous connecter pour voir les catégories.</p>
        </div>

        <div class="authenticated-content" id="authenticatedContent" style="display: none;">
            <div class="data-list" id="dataList"></div>
            <div class="pagination-info" id="paginationInfo" style="display: none;"></div>
        </div>
    </main>

    <x-mobile.bottom-nav />

    <x-mobile.footer />

    <script>
        async function loadCategories() {
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
                const data = await apiRequest('/categories?status=1');
                loadingState.style.display = 'none';
                authenticatedContent.style.display = 'block';

                if (data.data && data.data.length > 0) {
                    itemCount.textContent = data.total + ' catégorie(s)';

                    dataList.innerHTML = data.data.map(category => `
                        <div class="data-card">
                            <div class="data-card-header">
                                <h3>${category.name}</h3>
                                <span class="badge badge-${category.status === 1 ? 'success' : 'danger'}">
                                    ${category.status === 1 ? 'Actif' : 'Bloqué'}
                                </span>
                            </div>
                            <div class="data-card-body">
                                <div class="data-item">
                                    <span class="data-label">Créé le:</span>
                                    <span class="data-value">${category.created_at ? new Date(category.created_at).toLocaleDateString('fr-FR') : '-'}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    dataList.innerHTML = '<p class="empty-message">Aucune catégorie trouvée.</p>';
                }
            } catch (error) {
                loadingState.style.display = 'none';
                authenticatedContent.style.display = 'block';
                dataList.innerHTML = '<p class="empty-message">Erreur lors du chargement des catégories.</p>';
                showToast('Erreur lors du chargement des catégories');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadCategories();
        });
    </script>
</body>

</html>
