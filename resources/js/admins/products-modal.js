document.addEventListener("DOMContentLoaded", function () {
    // ============== FONCTIONS UTILITAIRES ==============
    // Récupérer le token CSRF
    function getCsrfToken() {
        const meta = document.querySelector('meta[name="csrf-token"]');
        return meta ? meta.content : "";
    }
    // Récupérer les URLs depuis les data attributes
    function getRoutes() {
        const modal = document.getElementById("productModal");
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
    // ============== MODAL D'AJOUT DE PRODUIT ==============
    // Ouvrir le modal d'ajout de produit
    window.openModal = function (event) {
        if (event) event.preventDefault();
        document.getElementById("productAddModal").style.display = "flex";
        document.getElementById("productAddModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };
    // Fermer le modal d'ajout de produit
    window.closeProductModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("productAddModal").style.display = "none";
        document.getElementById("productAddModal").classList.remove("active");
        document.body.style.overflow = "";
        // Réinitialiser le formulaire
        const form = document.getElementById("createProductForm");
        if (form) form.reset();
        // Masquer le conteneur de prix détaillé
        const detailedContainer = document.getElementById(
            "detailedPriceContainer",
        );
        if (detailedContainer) {
            detailedContainer.style.display = "none";
        }
    };
    // Afficher/masquer le prix détaillé
    window.toggleDetailedPrice = function () {
        const container = document.getElementById("detailedPriceContainer");
        const btn = event.target;
        if (
            container.style.display === "none" ||
            container.style.display === ""
        ) {
            container.style.display = "block";
            btn.textContent = "- Retirer le prix détaillé";
        } else {
            container.style.display = "none";
            if (container.querySelector("input")) {
                container.querySelector("input").value = "";
            }
            btn.textContent = "+ Ajouter un prix détaillé";
        }
    };

    // ============== MODAL DE DÉTAILS DU PRODUIT ==============
    window.openUserModal = function (
        id,
        product_name,
        post_scriptum,
        single_price,
        detailed_price,
        status,
        createdAt,
        updatedAt,
        branchName,
        categoryName,
        branchId,
        categoryId,
    ) {
        const csrf = getCsrfToken();
        const routes = getRoutes();

        // Remplir les informations
        document.getElementById("modalProductName").textContent = product_name;
        document.getElementById("modalPostScriptum").textContent =
            post_scriptum;
        document.getElementById("modalSinglePrice").textContent = single_price;
        document.getElementById("modalDetailedPrice").textContent =
            detailed_price;
        document.getElementById("modalBranchName").textContent = branchName;
        document.getElementById("modalCategoryName").textContent = categoryName;
        document.getElementById("modalCreatedAt").textContent = createdAt;
        document.getElementById("modalUpdatedAt").textContent = updatedAt;

        // Stocker les IDs dans des spans cachés
        document.getElementById("modalBranchId").textContent = branchId;
        document.getElementById("modalCategoryId").textContent = categoryId;
        // Status avec style
        const statusText = { 1: "Actif", 2: "Corbeille" };
        const statusClass = { 1: "completed", 2: "process" };
        const statusSpan = `<span class="status ${statusClass[status]}">${statusText[status]}</span>`;
        document.getElementById("modalStatus").innerHTML = statusSpan;
        // Boutons d'actions selon le status
        let actionsHtml = "";
        if (status === 1) {
            actionsHtml = `
                <form onsubmit="openEditModal(${id}, '${product_name}', '${post_scriptum}', ${single_price}, '${detailed_price}', '${branchId}', '${categoryId}'); return false;">
                    <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
                </form>
                <form action="${routes.block}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="prod_id" value="${id}">
                    <p><button type="submit" class="action-btn block-btn">Mettre à la corbeille</button></p>
                </form>
                <form action="${routes.erase}" method="POST" id="eraseForm">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="prod_id" value="${id}">
                    <p><button type="button" onclick="openConfirmEraseModal(document.getElementById('eraseForm'))" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p>
                </form>
            `;
        } else if (status === 2) {
            actionsHtml = `
                <form onsubmit="openEditModal(${id}, '${product_name}', '${post_scriptum}', ${single_price}, '${detailed_price}', '${branchId}', '${categoryId}'); return false;">
                    <p><button type="submit" class="action-btn edit-btn">Modifier</button></p>
                </form>
                <form action="${routes.restore}" method="POST">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="prod_id" value="${id}">
                    <p><button type="submit" class="action-btn restore-btn">Restaurer</button></p>
                </form>
                <form action="${routes.erase}" method="POST" id="eraseForm">
                    <input type="hidden" name="_token" value="${getCsrfToken()}">
                    <input type="hidden" name="prod_id" value="${id}">
                    <p><button type="button" onclick="openConfirmEraseModal(document.getElementById('eraseForm'))" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p>
                </form>
            `;
        }

        document.getElementById("modalActions").innerHTML = actionsHtml;
        document.getElementById("productModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };
    // Fermer le modal utilisateur
    window.closeUserModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("productModal").classList.remove("active");
        document.body.style.overflow = "";
    };
    // ============== MODAL DE CONFIRMATION ==============
    let currentEraseForm = null;
    window.openConfirmEraseModal = function (form) {
        currentEraseForm = form;
        document.getElementById("confirmEraseModal").style.display = "flex";
        document.body.style.overflow = "hidden";
    };
    window.closeConfirmEraseModal = function () {
        document.getElementById("confirmEraseModal").style.display = "none";
        document.body.style.overflow = "";
        currentEraseForm = null;
    };
    document
        .getElementById("confirmEraseBtn")
        .addEventListener("click", function () {
            if (currentEraseForm) {
                currentEraseForm.submit();
            }
        });
    // ============== MODAL D'ÉDITION ==============
    window.openEditModal = function (
        id,
        product_name,
        post_scriptum,
        single_price,
        detailed_price,
        branchId,
        categoryId,
    ) {
        // Fermer le modal de détails
        document.getElementById("productModal").classList.remove("active");
        // Remplir le formulaire
        document.getElementById("editProductId").value = id;
        document.getElementById("editProductName").value = product_name;
        document.getElementById("editPostScriptum").value =
            post_scriptum === "Aucun" ? "" : post_scriptum;
        document.getElementById("editSinglePrice").value = single_price;
        document.getElementById("editDetailedPrice").value =
            detailed_price === "Aucun" ? "" : detailed_price;

        // Pour les selects, utiliser les IDs
        setTimeout(function () {
            document.getElementById("editBranchId").value = branchId;
            document.getElementById("editCategoryId").value = categoryId;
        }, 100);
        // Ouvrir le modal d'édition
        const editModal = document.getElementById("editProductModal");
        if (editModal) {
            editModal.style.display = "flex";
            editModal.classList.add("active");
            document.body.style.overflow = "hidden";
        }
    };
    // Fermer le modal d'édition
    window.closeEditModal = function () {
        const editModal = document.getElementById("editProductModal");
        if (editModal) {
            editModal.style.display = "none";
            editModal.classList.remove("active");
        }
        document.body.style.overflow = "";
    };
    // ============== FERMETURE AVEC TOUCHE ESC ==============
    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeUserModal();
            window.closeProductModal();
            window.closeEditModal();
            window.closeConfirmEraseModal();
        }
    });
});
