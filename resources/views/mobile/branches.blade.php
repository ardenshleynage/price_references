<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <x-mobile.navbar />

    <main class="main-content">
        <div class="page-header">
            <h1>Branches</h1>
            <span class="item-count" id="itemCount">0 branche(s)</span>
        </div>

        <div class="loading-state" id="loadingState">
            <div class="spinner-large"></div>
            <p>Chargement des branches...</p>
        </div>

        <div class="not-auth-content" id="notAuthContent" style="display: none;">
            <p>Veuillez vous connecter pour voir les branches.</p>
        </div>

        <div class="authenticated-content" id="authenticatedContent" style="display: none;">
            <div class="data-list" id="dataList"></div>
            <div class="pagination-info" id="paginationInfo" style="display: none;"></div>
        </div>
    </main>

    <x-mobile.bottom-nav />

    <x-mobile.footer />

    <script>
        async function loadBranches() {
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
                const data = await apiRequest('/branches?status=1');
                loadingState.style.display = 'none';
                authenticatedContent.style.display = 'block';

                if (data.data && data.data.length > 0) {
                    itemCount.textContent = data.total + ' branche(s)';

                    dataList.innerHTML = data.data.map(branch => `
                        <div class="data-card">
                            <div class="data-card-header">
                                <h3>${branch.name}</h3>
                                <span class="badge badge-${branch.status === 1 ? 'success' : 'danger'}">
                                    ${branch.status === 1 ? 'Actif' : 'Bloqué'}
                                </span>
                            </div>
                            <div class="data-card-body">
                                <div class="data-item">
                                    <span class="data-label">Localisation:</span>
                                    <span class="data-value">${branch.location || '-'}</span>
                                </div>
                                <div class="data-item">
                                    <span class="data-label">Créé le:</span>
                                    <span class="data-value">${branch.created_at ? new Date(branch.created_at).toLocaleDateString('fr-FR') : '-'}</span>
                                </div>
                            </div>
                        </div>
                    `).join('');
                } else {
                    dataList.innerHTML = '<p class="empty-message">Aucune branche trouvée.</p>';
                }
            } catch (error) {
                loadingState.style.display = 'none';
                authenticatedContent.style.display = 'block';
                dataList.innerHTML = '<p class="empty-message">Erreur lors du chargement des branches.</p>';
                showToast('Erreur lors du chargement des branches');
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            loadBranches();
        });
    </script>
</body>

</html>
