<div class="modal-overlay active" style="z-index: 3000; backdrop-filter: blur(3px);"
    wire:click="cancelErase">
    <div class="login modal-content" style="position: relative; margin: 0; width: 400px; max-width: 90%;"
        wire:click.stop>
        <button class="modal-close" wire:click="cancelErase" aria-label="Fermer">&times;</button>
        <div class="login-triangle"></div>
        <h2 class="login-header">Confirmation</h2>
        <div class="login-container">
            <p style="text-align: center;">Êtes-vous sûr de vouloir supprimer définitivement cette branche ?
            </p>
            <div style="display: flex; gap: 10px; margin-top: 20px;">
                <button type="button" wire:click="cancelErase"
                    style="flex: 1; background: #ebebeb; color: #555; border: 1px solid #bbb; padding: 12px; border-radius: 4px; cursor: pointer;">Non</button>
                <button type="button" wire:click="erase"
                    style="flex: 1; background: #c0392b; color: white; border: none; padding: 12px; border-radius: 4px; cursor: pointer;">Oui</button>
            </div>
        </div>
    </div>
</div>
