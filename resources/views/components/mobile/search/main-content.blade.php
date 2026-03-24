    <main class="main-content">
        <div class="page-header">
            <button class="back-btn" onclick="goBack()">
                <i class='bx bx-arrow-back'></i>
            </button>
            <h1>Recherche</h1>
            <span style="width: 40px;"></span>
        </div>

        <div class="search-page-container">
            <div class="search-input-container-page">
                <i class='bx bx-search'></i>
                <input type="text" id="searchInput" class="search-input-page" placeholder="Rechercher..."
                    autocomplete="off" autofocus>
            </div>

            <div class="loading-state" id="searchLoading" style="display: none;">
                <div class="spinner-large"></div>
                <p>Recherche en cours...</p>
            </div>

            <div class="not-auth-content" id="notAuthContent" style="display: none;">
                <p>Veuillez vous connecter pour effectuer une recherche.</p>
            </div>

            <div class="search-results-page" id="searchResults">
                <p class="search-hint-page">Tapez pour rechercher...</p>
            </div>
        </div>
    </main>
    {{ $slot }}
