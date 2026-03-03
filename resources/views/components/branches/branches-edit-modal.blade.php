<div id="editBrancheModal" class="modal-overlay" style="display: none;">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" onclick="closeEditModal()" aria-label="Fermer"
            style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 28px; font-weight: bold; color: #fff; cursor: pointer;">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Modifier la branche</h2>
        <div class="login-container">
            <form id="editBrancheForm" action="{{ route('branches.update') }}" method="POST">
                @csrf

                <!-- ID caché - envoyé dans POST, pas dans URL -->
                <input type="hidden" id="editBrancheId" name="branche_id" value="">

                <p>
                    <input type="text" id="editBrancheName" name="branche_name" required
                        placeholder="Nom de la branche">
                </p>

                <p>
                    <button type="submit" class="action-btn"
                        style="background: #28d; color: white; border: none; padding: 12px; width: 100%; border-radius: 4px; cursor: pointer;">
                        Enregistrer les modifications
                    </button>
                </p>
            </form>
        </div>
    </div>
</div>
