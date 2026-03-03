<div id="searchUserModal" class="modal-overlay" 
    data-block-url="{{ route('users.block') }}"
    data-unblock-url="{{ route('users.unblock') }}"
    data-delete-url="{{ route('users.delete') }}"
    data-restore-url="{{ route('users.restore') }}"
    data-erase-url="{{ route('users.erase') }}"
    onclick="closeSearchUserModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeSearchUserModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>
            <h2 class="login-header">Détails utilisateur</h2>
            <div class="login-container">
                <p><strong>Nom d'utilisateur :</strong> <span id="searchModalUserUsername"></span></p>
                <p><strong>Rôle :</strong> <span id="searchModalUserRole"></span></p>
                <p><strong>Dernière connexion :</strong> <span id="searchModalUserLastConnect"></span></p>
                <p><strong>Crée le :</strong> <span id="searchModalUserCreatedAt"></span></p>
                <p><strong>Modifié le :</strong> <span id="searchModalUserUpdatedAt"></span></p>
                <p><strong>Status :</strong> <span id="searchModalUserStatus"></span></p>
                <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
                <div id="searchModalUserActions"></div>
            </div>
        </div>
    </div>
