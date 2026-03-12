document.addEventListener("DOMContentLoaded", function () {
    // Récupérer le token CSRF
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }
    // Récupérer les URLs depuis les data attributes
    function getRoutes() {
        const modal = document.getElementById("branchesModal");
        if (!modal) return null;
        return {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.permanentDeleteUrl,
            update: modal.dataset.updateUrl,
        };
    }
    // Ouvrir le modal utilisateur
    window.openUserModal = function (
        id,
        branche_name,
        status,
        createdAt,
        updatedAt,
    ) {
        const csrf = getCsrfToken();
        const routes = getRoutes();

        // Remplir les informations
        document.getElementById("modalBrancheName").textContent = branche_name;
        document.getElementById("modalCreatedAt").textContent = createdAt;
        document.getElementById("modalUpdatedAt").textContent = updatedAt;
        // Rôle
        // Status avec style
        const statusText = { 1: "Actif", 2: "Corbeille" };
        const statusClass = { 1: "completed", 2: "process" };
        const statusSpan = `<span class="status ${statusClass[status]}">${statusText[status]}</span>`;
        document.getElementById("modalStatus").innerHTML = statusSpan;
        // Boutons d'actions selon le status
        let actionsHtml = "";
        if (status === 1) {
            actionsHtml = `
        <form onsubmit="openEditModal(${id}, '${branche_name}'); return false;">
            <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
        </form>

                <form action="${routes.block}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="branche_id" value="${id}">
                    <p><button type="submit" class="action-btn block-btn">Mettre à la corbeille</button></p>
                </form>
                <form action="${routes.erase}" method="POST" id="eraseForm">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="branche_id" value="${id}">
                    <p><button type="button" onclick="openConfirmEraseModal(document.getElementById('eraseForm'))" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p>
                </form>
            `;
        } else if (status === 2) {
            actionsHtml = `
        <form onsubmit="openEditModal(${id}, '${branche_name}'); return false;">
            <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
        </form>

                <form action="${routes.restore}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="branche_id" value="${id}">
                    <p><button type="submit" class="action-btn restore-btn">Restaurer</button></p>
                </form>
                <form action="${routes.erase}" method="POST" id="eraseForm">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="branche_id" value="${id}">
                    <p><button type="button" onclick="openConfirmEraseModal(document.getElementById('eraseForm'))" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p>
                </form>
            `;
        }

        document.getElementById("modalActions").innerHTML = actionsHtml;
        document.getElementById("branchesModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };
    // Fermer le modal utilisateur
    window.closeUserModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("branchesModal").classList.remove("active");
        document.body.style.overflow = "";
    };
    // Fermer avec ESC
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeUserModal();
        }
    });
    let currentEraseForm = null;
    // Ouvrir le modal de confirmation
    window.openConfirmEraseModal = function (form) {
        currentEraseForm = form;
        document.getElementById("confirmEraseModal").style.display = "flex";
        document.body.style.overflow = "hidden";
    };
    // Fermer le modal de confirmation
    window.closeConfirmEraseModal = function () {
        document.getElementById("confirmEraseModal").style.display = "none";
        document.body.style.overflow = "";
        currentEraseForm = null;
    };
    // Confirmer et soumettre
    document
        .getElementById("confirmEraseBtn")
        .addEventListener("click", function () {
            if (currentEraseForm) {
                currentEraseForm.submit();
            }
        });
    // Fermer avec ESC pour le modal de confirmation
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeConfirmEraseModal();
        }
    });

    window.openEditModal = function (id, brancheName) {
        // Fermer le modal de détails
        document.getElementById("branchesModal").classList.remove("active");

        // Pré-remplir le formulaire
        document.getElementById("editBrancheId").value = id;
        document.getElementById("editBrancheName").value = brancheName;

        // Ouvrir le modal d'édition
        document.getElementById("editBrancheModal").style.display = "flex";
        document.getElementById("editBrancheModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };
    // Fonction pour fermer le modal d'édition
    window.closeEditModal = function () {
        document.getElementById("editBrancheModal").style.display = "none";
        document.getElementById("editBrancheModal").classList.remove("active");
        document.body.style.overflow = "";
    };
    // Fermer avec ESC pour les deux modals
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeUserModal();
            window.closeEditModal();
        }
    });
});
