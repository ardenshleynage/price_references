    <div class="modal-overlay" id="confirmEraseUserModal">
        <div class="modal-content-mobile">
            <button class="modal-close" onclick="closeConfirmEraseUserModal()">
                <i class='bx bx-x'></i>
            </button>

            <div class="modal-header-mobile">
                <h2>Confirmer la suppression</h2>
            </div>

            <div class="modal-body-mobile">
                <p style="text-align: center; margin-bottom: 20px; color: var(--text-color);">
                    Êtes-vous sûr de vouloir supprimer définitivement cet utilisateur ? Cette action est irréversible.
                </p>

                <button class="btn btn-danger btn-full" onclick="confirmEraseUserFromSearch()">
                    <i class='bx bxs-trash-alt'></i> Supprimer définitivement
                </button>
                <button class="btn btn-secondary btn-full" onclick="closeConfirmEraseUserModal()" style="margin-top: 10px;">
                    Annuler
                </button>
            </div>
        </div>
    </div>
    {{ $slot }}
