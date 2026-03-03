// Get routes from data attributes
let currentEraseForm = null;

function getCategoryRoutes() {
    const modal = document.getElementById("searchCategoryModal");
    return modal
        ? {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.eraseUrl,
        }
        : {};
}

function getBranchRoutes() {
    const modal = document.getElementById("searchBranchModal");
    return modal
        ? {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.eraseUrl,
        }
        : {};
}

function getProductRoutes() {
    const modal = document.getElementById("searchProductModal");
    return modal
        ? {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.eraseUrl,
        }
        : {};
}

function getUserRoutes() {
    const modal = document.getElementById("searchUserModal");
    return modal
        ? {
            block: modal.dataset.blockUrl,
            unblock: modal.dataset.unblockUrl,
            delete: modal.dataset.deleteUrl,
            restore: modal.dataset.restoreUrl,
            erase: modal.dataset.eraseUrl,
        }
        : {};
}

// Product Modal Functions
window.openProductModal = function (
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
    status = parseInt(status);
    document.getElementById("searchModalProductName").textContent =
        product_name;
    document.getElementById("searchModalPostScriptum").textContent =
        post_scriptum;
    document.getElementById("searchModalSinglePrice").textContent =
        single_price;
    document.getElementById("searchModalDetailedPrice").textContent =
        detailed_price;
    document.getElementById("searchModalBranchName").textContent = branchName;
    document.getElementById("searchModalCategoryName").textContent =
        categoryName;
    document.getElementById("searchModalCreatedAt").textContent = createdAt;
    document.getElementById("searchModalUpdatedAt").textContent = updatedAt;
    document.getElementById("searchModalBranchId").textContent = branchId;
    document.getElementById("searchModalCategoryId").textContent = categoryId;

    const statusText = { 1: "Actif", 2: "Bloqué", 0: "Supprimé" };
    const statusClass = { 1: "completed", 2: "pending", 0: "process" };
    document.getElementById("searchModalStatus").innerHTML =
        '<span class="status ' +
        statusClass[status] +
        '">' +
        statusText[status] +
        "</span>";

    const routes = getProductRoutes();
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchQuery =
        new URLSearchParams(window.location.search).get("q") || "";
    let actionsHtml = "";

    if (status === 1 || status === 2) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="alert(\'Fonctionnalité de modification bientôt disponible!\')">Modifier</button></p>';
    }
    if (status === 1) {
        actionsHtml +=
            '<form action="' +
            routes.block +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn block-btn">Bloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 2) {
        actionsHtml +=
            '<form action="' +
            routes.unblock +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn unblock-btn">Débloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 0) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="alert(\'Impossible de modifier un élément supprimé. Restaurez-le d\'abord.\')">Modifier</button></p>';
        actionsHtml +=
            '<form action="' +
            routes.restore +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn restore-btn">Restaurer</button></p></form>' +
            '<form action="' +
            routes.erase +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="prod_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="button" onclick="openSearchConfirmEraseModal(this.form)" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p></form>';
    }

    document.getElementById("searchModalProductActions").innerHTML =
        actionsHtml;
    document.getElementById("searchProductModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchProductModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchProductModal").classList.remove("active");
    document.body.style.overflow = "";
};

// User Modal Functions
window.openUserModal = function (
    id,
    username,
    role,
    status,
    lastConnect,
    createdAt,
    updatedAt,
) {
    status = parseInt(status);
    document.getElementById("searchModalUserUsername").textContent = username;
    const roleText = { 1: "Super Admin", 2: "Admin", 3: "Utilisateur" };
    document.getElementById("searchModalUserRole").textContent =
        roleText[role] || "Inconnu";
    document.getElementById("searchModalUserLastConnect").textContent =
        lastConnect;
    document.getElementById("searchModalUserCreatedAt").textContent = createdAt;
    document.getElementById("searchModalUserUpdatedAt").textContent = updatedAt;

    const statusText = { 1: "Actif", 2: "Bloqué", 0: "Supprimé" };
    const statusClass = { 1: "completed", 2: "pending", 0: "process" };
    document.getElementById("searchModalUserStatus").innerHTML =
        '<span class="status ' +
        statusClass[status] +
        '">' +
        statusText[status] +
        "</span>";

    const routes = getUserRoutes();
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchQuery =
        new URLSearchParams(window.location.search).get("q") || "";
    let actionsHtml = "";

    if (status === 1 || status === 2) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="window.location.href=\'/users/edit-from-search/' + id + '?q=' + searchQuery + '\'">Modifier</button></p>';
    }
    if (status === 1) {
        actionsHtml +=
            '<form action="' +
            routes.block +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn block-btn">Bloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 2) {
        actionsHtml +=
            '<form action="' +
            routes.unblock +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn unblock-btn">Débloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 0) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="alert(\'Impossible de modifier un élément supprimé. Restaurez-le d\'abord.\')">Modifier</button></p>';
        actionsHtml +=
            '<form action="' +
            routes.restore +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn restore-btn">Restaurer</button></p></form>' +
            '<form action="' +
            routes.erase +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="user_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="button" onclick="openSearchConfirmEraseModal(this.form)" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p></form>';
    }

    document.getElementById("searchModalUserActions").innerHTML = actionsHtml;
    document.getElementById("searchUserModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchUserModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchUserModal").classList.remove("active");
    document.body.style.overflow = "";
};

// Category Modal Functions
window.openCategoryModal = function (
    id,
    categoryName,
    status,
    createdAt,
    updatedAt,
) {
    status = parseInt(status);
    document.getElementById("searchModalCategoryItemName").textContent =
        categoryName;
    document.getElementById("searchModalCategoryItemCreatedAt").textContent =
        createdAt;
    document.getElementById("searchModalCategoryItemUpdatedAt").textContent =
        updatedAt;

    const statusText = { 1: "Actif", 2: "Bloqué", 0: "Supprimé" };
    const statusClass = { 1: "completed", 2: "pending", 0: "process" };
    document.getElementById("searchModalCategoryItemStatus").innerHTML =
        '<span class="status ' +
        statusClass[status] +
        '">' +
        statusText[status] +
        "</span>";

    const routes = getCategoryRoutes();
    // Fallback to hardcoded routes if data attributes not found
    const blockUrl = routes.block || "/categories/block";
    const unblockUrl = routes.unblock || "/categories/unblock";
    const deleteUrl = routes.delete || "/categories/delete";
    const restoreUrl = routes.restore || "/categories/restore";
    const eraseUrl = routes.erase || "/categories/erase";
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchQuery =
        new URLSearchParams(window.location.search).get("q") || "";
    let actionsHtml = "";

    if (status === 1 || status === 2) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="window.location.href=\'/categories/edit-from-search/' +
            id +
            "?q=" +
            searchQuery +
            "'\">Modifier</button></p>";
    }
    if (status === 1) {
        actionsHtml +=
            '<form action="' +
            blockUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn block-btn">Bloquer</button></p></form>' +
            '<form action="' +
            deleteUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 2) {
        actionsHtml +=
            '<form action="' +
            unblockUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn unblock-btn">Débloquer</button></p></form>' +
            '<form action="' +
            deleteUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 0) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="alert(\'Impossible de modifier un élément supprimé. Restaurez-le d\'abord.\')">Modifier</button></p>';
        actionsHtml +=
            '<form action="' +
            restoreUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn restore-btn">Restaurer</button></p></form>' +
            '<form action="' +
            eraseUrl +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="category_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="button" onclick="openSearchConfirmEraseModal(this.form)" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p></form>';
    }

    document.getElementById("searchModalCategoryActions").innerHTML =
        actionsHtml;
    document.getElementById("searchCategoryModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchCategoryModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchCategoryModal").classList.remove("active");
    document.body.style.overflow = "";
};

// Category Edit Modal Functions
window.openSearchCategoryEditModal = function (id) {
    const categoryName = document.getElementById(
        "searchModalCategoryItemName",
    ).textContent;
    document.getElementById("searchCategoryModal").classList.remove("active");
    document.getElementById("searchEditCategoryId").value = id;
    document.getElementById("searchEditCategoryName").value = categoryName;
    document.getElementById("searchEditCategoryQ").value =
        new URLSearchParams(window.location.search).get("q") || "";
    document.getElementById("searchCategoryEditModal").style.display = "flex";
    document.getElementById("searchCategoryEditModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchCategoryEditModal = function () {
    document.getElementById("searchCategoryEditModal").style.display = "none";
    document
        .getElementById("searchCategoryEditModal")
        .classList.remove("active");
    document.body.style.overflow = "";
};

// Branch Modal Functions
window.openBranchModal = function (
    id,
    branchName,
    status,
    createdAt,
    updatedAt,
) {
    status = parseInt(status);
    document.getElementById("searchModalBranchItemName").textContent =
        branchName;
    document.getElementById("searchModalBranchItemCreatedAt").textContent =
        createdAt;
    document.getElementById("searchModalBranchItemUpdatedAt").textContent =
        updatedAt;

    const statusText = { 1: "Actif", 2: "Bloqué", 0: "Supprimé" };
    const statusClass = { 1: "completed", 2: "pending", 0: "process" };
    document.getElementById("searchModalBranchItemStatus").innerHTML =
        '<span class="status ' +
        statusClass[status] +
        '">' +
        statusText[status] +
        "</span>";

    const routes = getBranchRoutes();
    let csrfToken = document.querySelector('meta[name="csrf-token"]').content;
    let searchQuery =
        new URLSearchParams(window.location.search).get("q") || "";
    let actionsHtml = "";

    if (status === 1 || status === 2) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="window.location.href=\'/branches/edit-from-search/' +
            id +
            "?q=" +
            searchQuery +
            "'\">Modifier</button></p>";
    }
    if (status === 1) {
        actionsHtml +=
            '<form action="' +
            routes.block +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn block-btn">Bloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 2) {
        actionsHtml +=
            '<form action="' +
            routes.unblock +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn unblock-btn">Débloquer</button></p></form>' +
            '<form action="' +
            routes.delete +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn delete-btn">Supprimer</button></p></form>';
    } else if (status === 0) {
        actionsHtml =
            '<p><button type="button" class="action-btn edit-btn" onclick="alert(\'Impossible de modifier un élément supprimé. Restaurez-le d\'abord.\')">Modifier</button></p>';
        actionsHtml +=
            '<form action="' +
            routes.restore +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="submit" class="action-btn restore-btn">Restaurer</button></p></form>' +
            '<form action="' +
            routes.erase +
            '" method="POST"><input type="hidden" name="_token" value="' +
            csrfToken +
            '"><input type="hidden" name="branche_id" value="' +
            id +
            '"><input type="hidden" name="q" value="' +
            searchQuery +
            '"><p><button type="button" onclick="openSearchConfirmEraseModal(this.form)" class="action-btn delete-permanent-btn">Supprimer définitivement</button></p></form>';
    }

    document.getElementById("searchModalBranchActions").innerHTML = actionsHtml;
    document.getElementById("searchBranchModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchBranchModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchBranchModal").classList.remove("active");
    document.body.style.overflow = "";
};

// General modal functions
window.openConfirmModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "flex";
        document.body.style.overflow = "hidden";
    }
};

window.closeConfirmModal = function (modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.style.display = "none";
        document.body.style.overflow = "";
    }
};

// For backward compatibility
window.openSearchConfirmEraseModal = function (form) {
    currentEraseForm = form;
    document.getElementById("searchConfirmEraseModal").style.display = "flex";
    document.body.style.overflow = "hidden";
};

window.closeSearchConfirmEraseModal = function () {
    document.getElementById("searchConfirmEraseModal").style.display = "none";
    document.body.style.overflow = "";
    currentEraseForm = null;
};

document.addEventListener("DOMContentLoaded", function () {
    // Confirm erase button
    const confirmBtn = document.getElementById("searchConfirmEraseBtn");
    if (confirmBtn) {
        confirmBtn.addEventListener("click", function () {
            if (currentEraseForm) {
                currentEraseForm.submit();
            }
        });
    }

    // Edit category buttons
    document.addEventListener("click", function (e) {
        if (
            e.target &&
            e.target.classList.contains("edit-btn") &&
            e.target.dataset.id
        ) {
            const id = e.target.dataset.id;
            openSearchCategoryEditModal(id);
        }
    });

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            closeSearchProductModal();
            closeSearchUserModal();
            closeSearchCategoryModal();
            closeSearchBranchModal();
            closeSearchConfirmEraseModal();
            closeSearchCategoryEditModal();
        }
    });
});
