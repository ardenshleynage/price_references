<div id="searchConfirmEraseModal" class="modal-overlay"
    style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 3000; justify-content: center; align-items: center; backdrop-filter: blur(3px);">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;">
        <button class="modal-close" onclick="closeSearchConfirmEraseModal()" aria-label="Fermer"
            style="position: absolute; top: 10px; right: 15px; background: none; border: none; font-size: 28px; font-weight: bold; color: #fff; cursor: pointer; z-index: 10; width: 30px; height: 30px; display: flex; align-items: center; justify-content: center; border-radius: 50%;">&times;</button>
        <div class="login-triangle"
            style="width: 0; margin-right: auto; margin-left: auto; border: 12px solid transparent; border-bottom-color: #28d;">
        </div>
        <h2 class="login-header"
            style="background: #28d; padding: 20px; font-size: 1.4em; font-weight: normal; text-align: center; text-transform: uppercase; color: #fff;">
            Confirmation</h2>
        <div class="confirm-erase-container">
            <p class="confirm-erase-text">Êtes-vous sûr de vouloir supprimer définitivement cet élément ?</p>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" onclick="closeSearchConfirmEraseModal()"
                    class="confirm-erase-cancel">Non</button>
                <button type="button" id="searchConfirmEraseBtn" class="confirm-erase-confirm"
                    style="flex: 1; padding: 16px; background: #DB504A; border: none; color: #fff; cursor: pointer; font-family: inherit; font-size: 0.95em; border-radius: 4px;">Oui</button>
            </div>
        </div>
    </div>
</div>

{{ $slot }}
