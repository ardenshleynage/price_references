<div id="userModal" data-block-url="{{ route('users.block') }}" data-unblock-url="{{ route('users.unblock') }}"
    data-delete-url="{{ route('users.delete') }}" data-restore-url="{{ route('users.restore') }}"
    data-permanent-delete-url="{{ route('users.erase') }}" class="modal-overlay" onclick="closeUserModal(event)">
    <div class="login modal-content" onclick="event.stopPropagation()">
        <button class="modal-close" onclick="closeUserModal()" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Détails Utilisateur</h2>
        <div class="login-container">
            <p><strong>Nom d'utilisateur :</strong> <span id="modalUsername"></span></p>
            <p><strong>E-mail :</strong> <span id="modalEmail"></span></p>
            <p><strong>Dernière connexion :</strong> <span id="modalLastConnect"></span></p>
            <p><strong>Rôle :</strong> <span id="modalRole"></span></p>
            <p><strong>Status :</strong> <span id="modalStatus"></span></p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            <p><strong>Crée le :</strong> <span id="modalCreatedAt"></span></p>
            <p><strong>Modifié le :</strong> <span id="modalUpdatedAt"></span></p>
            <hr style="margin: 15px 0; border: none; border-top: 1px solid #ddd;">
            <div id="modalActions">
            </div>
        </div>
    </div>
</div>

<div id="confirmEraseModal" class="modal-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 3000; justify-content: center; align-items: center; backdrop-filter: blur(3px);">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" onclick="closeConfirmEraseModal()" aria-label="Fermer"
            style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 28px; font-weight: bold; color: #fff; cursor: pointer; z-index: 10; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</button>
        <div class="login-triangle"
            style="width: 0; margin-right: auto; margin-left: auto; border: 12px solid transparent; border-bottom-color: #28d;">
        </div>
        <h2 class="login-header"
            style="background: #28d; padding: 20px; font-size: 1.4em; font-weight: normal; text-align: center; text-transform: uppercase; color: #fff;">
            Confirmation</h2>
        <div class="confirm-erase-container">
            <p class="confirm-erase-text">Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ?</p>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeConfirmEraseModal()" class="confirm-erase-cancel">Non</button>
                <button type="button" id="confirmEraseBtn" class="confirm-erase-confirm">Oui</button>
            </div>
        </div>
    </div>
</div>
{{ $slot }}
