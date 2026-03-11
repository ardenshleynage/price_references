document.addEventListener("DOMContentLoaded", function () {
    // Récupérer le token CSRF
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }
    // Récupérer les URLs depuis les data attributes
    function getRoutes() {
        const modal = document.getElementById("userModal");
        if (!modal) return null;
        return {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.permanentDeleteUrl,
        };
    }
    // Ouvrir le modal utilisateur
    window.openUserModal = function (
        id,
        username,
        email,
        role,
        status,
        lastConnect,
        createdAt,
        updatedAt,
    ) {
        const csrf = getCsrfToken();
        const routes = getRoutes();

        // Remplir les informations
        document.getElementById("modalUsername").textContent = username;
        document.getElementById("modalEmail").textContent = email;
        document.getElementById("modalLastConnect").textContent = lastConnect;
        document.getElementById("modalCreatedAt").textContent = createdAt;
        document.getElementById("modalUpdatedAt").textContent = updatedAt;
        // Rôle
        const roleText = { 1: "Super Admin", 2: "Admin", 3: "Utilisateur" };
        document.getElementById("modalRole").textContent =
            roleText[role] || "Inconnu";
        // Status avec style
        const statusText = { 1: "Actif", 2: "Bloqué", 0: "Supprimé" };
        const statusClass = { 1: "completed", 2: "pending", 0: "process" };
        const statusSpan = `<span class="status ${statusClass[status]}">${statusText[status]}</span>`;
        document.getElementById("modalStatus").innerHTML = statusSpan;
        // Boutons d'actions selon le status
        let actionsHtml = "";
        if (status === 1) {
            actionsHtml = `
                <form onsubmit="openEditUserModal(${id}, '${username}', '${email}', ${role}); return false;">
                    <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
                </form>
                <form action="${routes.block}" method="POST">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="user_id" value="${id}">
                    <p><button type="submit" class="action-btn block-btn">Bloquer</button></p>
                </form>
                <form action="${routes.delete}" method="POST">
                    <input type="hidden" name="_token" value="${csrf}">
                    <input type="hidden" name="user_id" value="${id}">
                    <p><button type="submit" class="action-btn delete-btn">Supprimer</button></p>
                </form>
            `;
        } else if (status === 2) {
            actionsHtml = `
                <form onsubmit="openEditUserModal(${id}, '${username}', '${email}', ${role}); return false;">
                    <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
                </form>
                <form action="${routes.unblock}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="user_id" value="${id}">
                    <p><button type="submit" class="action-btn unblock-btn">Débloquer</button></p>
                </form>
                <form action="${routes.delete}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="user_id" value="${id}">
                    <p><button type="submit" class="action-btn delete-btn">Supprimer</button></p>
                </form>
            `;
        } else if (status === 0) {
            actionsHtml = `
        <form onsubmit="openEditUserModal(${id}, '${username}', '${email}', ${role}); return false;">
            <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
        </form>
        <form action="${routes.restore}" method="POST">
            <input type="hidden" name="_token" value="${getCsrfToken()}">
            <input type="hidden" name="user_id" value="${id}">
            <p><button type="submit" class="action-btn restore-btn">Restaurer</button></p>
        </form>
        <form id="eraseForm" action="${routes.erase}" method="POST">
            <input type="hidden" name="_token" value="${getCsrfToken()}">
            <input type="hidden" name="user_id" value="${id}">
            <p><button type="button" onclick="openConfirmEraseModal(this.form)" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p>
        </form>
    `;
        }

        document.getElementById("modalActions").innerHTML = actionsHtml;
        document.getElementById("userModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };
    // Fermer le modal utilisateur
    window.closeUserModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("userModal").classList.remove("active");
        document.body.style.overflow = "";
    };
    // Fermer avec ESC
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeUserModal();
            window.closeEditUserModal();
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

    window.openEditUserModal = function (id, username, email, role) {
        // Fermer le modal de détails
        document.getElementById("userModal").classList.remove("active");

        // Remplir le formulaire
        document.getElementById("editUserId").value = id;
        document.getElementById("editUsername").value = username;
        document.getElementById("editUserEmail").value = email;

        setTimeout(function () {
            document.getElementById("editUserRole").value = role;
        }, 100);

        // Ouvrir le modal d'édition
        const editModal = document.getElementById("editUsersModal");
        if (editModal) {
            editModal.style.display = "flex";
            editModal.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    };
    // Fermer le modal d'édition utilisateur
    window.closeEditUserModal = function () {
        const editModal = document.getElementById("editUsersModal");
        if (editModal) {
            editModal.style.display = "none";
            editModal.classList.remove("active");
        }
        document.body.style.overflow = "";
    };
});
