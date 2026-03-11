<div id="branchesModal" class="modal-overlay" onclick="closeBranchModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeBranchModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails branche</h2>
        <div class="login-container">
            <p><strong>Nom de la branche :</strong> <span id="modalBrancheName"></span></p>
        </div>
    </div>
</div>
