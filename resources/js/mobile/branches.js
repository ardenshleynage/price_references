let currentStatus;
let userRole = 3;
let selectedBranch = null;

window.initPage = async function () {
    const user = getUser();
    userRole = parseInt(user.role) || 3;

    if (userRole === 1 || userRole === 2) {
        currentStatus = "all";
        document.getElementById("addBranchBtn").style.display = "flex";
    } else {
        currentStatus = 1;
    }

    loadTabs();
    loadBranches();
};

window.loadBranches = async function () {
    const loadingState = document.getElementById("loadingState");
    const notAuthContent = document.getElementById("notAuthContent");
    const authenticatedContent = document.getElementById(
        "authenticatedContent",
    );
    const dataList = document.getElementById("dataList");
    const itemCount = document.getElementById("itemCount");
    const paginationInfo = document.getElementById("paginationInfo");

    const user = getUser();
    if (!user.id || !user.token) {
        loadingState.style.display = "none";
        notAuthContent.style.display = "block";
        return;
    }

    try {
        let url = "/branches?status=" + currentStatus;
        if (currentStatus === "all") {
            url = "/branches?status=all";
        }

        const data = await apiRequest(url);
        loadingState.style.display = "none";
        authenticatedContent.style.display = "block";

        if (data.data && data.data.length > 0) {
            itemCount.textContent = data.total + " branche(s)";

            dataList.innerHTML = data.data
                .map(
                    (branch) => `
                <div class="data-card" onclick="openBranchModal(${branch.id})" style="cursor: pointer;">
                    <div class="data-card-header">
                        <h3>${branch.branche_name || "-"}</h3>
                        ${userRole !== 3 ? `<span class="badge ${getBranchStatusBadgeClass(branch.status)}">
                            ${getBranchStatusLabel(branch.status)}
                        </span>` : ''}
                    </div>
                    <div class="data-card-body">
                        <div class="data-item">
                            <span class="data-label">Créé le:</span>
                            <span class="data-value">${formatDate(branch.created_at)}</span>
                        </div>
                                            </div>
                        <div class="data-item">
                            <span class="data-label">Modifié le:</span>
                            <span class="data-value">${formatDate(branch.updated_at)}</span>
                        </div>
                    </div>
                </div>
            `,
                )
                .join("");

            if (data.last_page > 1) {
                paginationInfo.style.display = "block";
                paginationInfo.innerHTML = `Page ${data.current_page} sur ${data.last_page}`;
            } else {
                paginationInfo.style.display = "none";
            }
        } else {
            dataList.innerHTML =
                '<p class="empty-message">Aucune branche trouvée.</p>';
            paginationInfo.style.display = "none";
        }
    } catch (error) {
        loadingState.style.display = "none";
        authenticatedContent.style.display = "block";
        dataList.innerHTML =
            '<p class="empty-message">Erreur lors du chargement des branches.</p>';
        showToast("Erreur lors du chargement des branches");
    }
};

window.openBranchModal = async function (branchId) {
    const modal = document.getElementById("branchModal");

    try {
        let url = "/branches?status=all";
        const data = await apiRequest(url);
        const branch = data.data.find((b) => b.id === branchId);

        if (!branch) {
            showToast("Branche non trouvée");
            return;
        }

        selectedBranch = branch;

        document.getElementById("modalBranchName").textContent =
            branch.branche_name || "-";

        const statusBadge = document.getElementById("modalBranchStatusBadge");
        if (userRole !== 3) {
            statusBadge.className =
                "modal-status-badge " + getBranchStatusBadgeClass(branch.status);
            statusBadge.textContent = getBranchStatusLabel(branch.status);
            statusBadge.style.display = 'block';
        } else {
            statusBadge.style.display = 'none';
        }

        document.getElementById("modalBranchCreatedAt").textContent =
            formatDate(branch.created_at);
        document.getElementById("modalBranchUpdatedAt").textContent =
            formatDate(branch.updated_at);

        const actionsDiv = document.getElementById("modalBranchActions");
        let actionsHtml = "";
        const branchStatus = parseInt(branch.status);

        if (userRole === 1) {
            if (branchStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-warning" onclick="blockBranch(${branch.id})"><i class='bx bxs-block'></i> Bloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="unblockBranch(${branch.id})"><i class='bx bxs-check-circle'></i> Débloquer</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 0) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreBranch(${branch.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseBranchModal(${branch.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 2) {
            if (branchStatus === 1) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-danger-outline" onclick="deleteBranch(${branch.id})"><i class='bx bxs-trash'></i> Supprimer</button>
                `;
            } else if (branchStatus === 2) {
                actionsHtml += `
                    <button class="btn btn-primary" onclick="openEditBranchModal(${branch.id})"><i class='bx bxs-edit'></i> Modifier</button>
                    <button class="btn btn-success" onclick="restoreBranch(${branch.id})"><i class='bx bxs-trash-alt'></i> Restaurer</button>
                    <button class="btn btn-danger" onclick="openConfirmEraseBranchModal(${branch.id})"><i class='bx bxs-trash-alt'></i> Supprimer définitivement</button>
                `;
            }
        } else if (userRole === 3) {
            // Reader: no actions
        }

        actionsDiv.innerHTML = actionsHtml;
        modal.classList.add("active");
    } catch (error) {
        showToast("Erreur lors du chargement de la branche");
    }
};

window.closeBranchModal = function () {
    document.getElementById("branchModal").classList.remove("active");
    selectedBranch = null;
};

window.closeConfirmEraseBranchModal = function () {
    document
        .getElementById("confirmEraseBranchModal")
        .classList.remove("active");
};

window.openConfirmEraseBranchModal = function (id) {
    selectedBranch = { id: id };
    document.getElementById("confirmEraseBranchModal").classList.add("active");
};

window.openAddBranchModal = async function () {
    const modal = document.getElementById("addBranchModal");
    const form = document.getElementById("addBranchForm");
    form.reset();
    document.getElementById("addBranchError").style.display = "none";
    modal.classList.add("active");
};

window.closeAddBranchModal = function () {
    document.getElementById("addBranchModal").classList.remove("active");
};

window.openEditBranchModal = async function (id) {
    const modal = document.getElementById("editBranchModal");
    const form = document.getElementById("editBranchForm");
    form.reset();
    document.getElementById("editBranchError").style.display = "none";

    closeBranchModal();

    try {
        const data = await apiRequest("/branches?status=all");
        const branch = data.data.find((b) => b.id === id);

        if (!branch) {
            showToast("Branche non trouvée");
            return;
        }

        document.getElementById("editBranchId").value = branch.id;
        document.getElementById("editBranchName").value =
            branch.branche_name || "";

        modal.classList.add("active");
    } catch (error) {
        showToast("Erreur lors du chargement de la branche");
    }
};

window.closeEditBranchModal = function () {
    document.getElementById("editBranchModal").classList.remove("active");
};

window.blockBranch = async function (id) {
    try {
        await apiRequest("/branches/block", {
            method: "POST",
            body: JSON.stringify({ id: id }),
        });
        showToast("Branche bloquée");
        closeBranchModal();
        loadBranches();
        loadTabs();
    } catch (error) {
        showToast("Erreur lors du blocage");
    }
};

window.unblockBranch = async function (id) {
    try {
        await apiRequest("/branches/unblock", {
            method: "POST",
            body: JSON.stringify({ id: id }),
        });
        showToast("Branche débloquée");
        closeBranchModal();
        loadBranches();
        loadTabs();
    } catch (error) {
        showToast("Erreur lors du déblocage");
    }
};

window.deleteBranch = async function (id) {
    try {
        await apiRequest("/branches/delete", {
            method: "POST",
            body: JSON.stringify({ id: id }),
        });
        showToast("Branche supprimée");
        closeBranchModal();
        loadBranches();
        loadTabs();
    } catch (error) {
        showToast("Erreur lors de la suppression");
    }
};

window.restoreBranch = async function (id) {
    try {
        await apiRequest("/branches/restore", {
            method: "POST",
            body: JSON.stringify({ id: id }),
        });
        showToast("Branche restaurée");
        closeBranchModal();
        loadBranches();
        loadTabs();
    } catch (error) {
        showToast("Erreur lors de la restauration");
    }
};

window.confirmEraseBranch = async function () {
    if (!selectedBranch) return;
    try {
        await apiRequest("/branches/erase", {
            method: "POST",
            body: JSON.stringify({ id: selectedBranch.id }),
        });
        showToast("Branche supprimée définitivement");
        closeConfirmEraseBranchModal();
        closeBranchModal();
        loadBranches();
        loadTabs();
    } catch (error) {
        showToast("Erreur lors de la suppression");
    }
};

window.loadTabs = async function () {
    const statusTabs = document.getElementById("statusTabs");
    
    if (userRole === 3) {
        statusTabs.style.display = 'none';
        return;
    }
    
    statusTabs.style.display = 'flex';

    try {
        const counts = await apiRequest("/branches/counts");

        let tabsHtml = "";

        if (userRole === 1 || userRole === 2) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === "all" ? "active" : ""}" onclick="changeBranchStatus('all')">
                    Tous(${counts.total || 0})
                </button>
            `;
        }

        tabsHtml += `
            <button class="status-tab ${currentStatus === 1 ? "active" : ""}" onclick="changeBranchStatus(1)">
                Actif(${counts.active || 0})
            </button>
        `;

        if (userRole === 1) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 2 ? "active" : ""}" onclick="changeBranchStatus(2)">
                    Bloqué(${counts.blocked || 0})
                </button>
            `;
        }

        if (userRole === 2) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 2 ? "active" : ""}" onclick="changeBranchStatus(2)">
                    Corbeille(${counts.blocked || 0})
                </button>
            `;
        }

        if (userRole === 1) {
            tabsHtml += `
                <button class="status-tab ${currentStatus === 0 ? "active" : ""}" onclick="changeBranchStatus(0)">
                    Corbeille(${counts.deleted || 0})
                </button>
            `;
        }

        statusTabs.innerHTML = tabsHtml;
    } catch (error) {
        console.error("Error loading counts:", error);
    }
};

window.changeBranchStatus = function (status) {
    currentStatus = status;
    loadBranches();
    loadTabs();
};

window.getBranchStatusBadgeClass = function (status) {
    const s = parseInt(status);
    if (s === 1) return "badge-active";
    if (s === 2) return "badge-blocked";
    if (s === 0) return "badge-deleted";
    return "badge-active";
};

window.getBranchStatusLabel = function (status) {
    const s = parseInt(status);
    if (s === 1) return "Actif";
    if (s === 0) return "Supprimée";
    if (s === 2) {
        return userRole == 2 ? "Supprimée" : "Bloqué";
    }
    return "Inconnu";
};

window.formatDate = function (dateString) {
    if (!dateString) return "-";
    const date = new Date(dateString);
    return date.toLocaleDateString("fr-FR", {
        day: "2-digit",
        month: "2-digit",
        year: "numeric",
    });
};

document
    .getElementById("addBranchForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        const branchName = document.getElementById("addBranchName").value;
        const errorDiv = document.getElementById("addBranchError");
        const submitBtn = document.getElementById("addBranchSubmitBtn");

        if (!branchName) {
            errorDiv.textContent = "Le nom de la branche est obligatoire.";
            errorDiv.style.display = "block";
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="btn-spinner"></span>';
        errorDiv.style.display = "none";

        try {
            await apiRequest("/branches/create", {
                method: "POST",
                body: JSON.stringify({
                    branche_name: branchName,
                }),
            });

            showToast("Branche ajoutée avec succès");
            closeAddBranchModal();
            loadBranches();
            loadTabs();
        } catch (error) {
            let errorMessage = "Erreur lors de la création de la branche";
            if (
                error.response &&
                error.response.data &&
                error.response.data.error
            ) {
                errorMessage = error.response.data.error;
            } else if (error.message) {
                errorMessage = error.message;
            }
            errorDiv.textContent = errorMessage;
            errorDiv.style.display = "block";
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="btn-text">Ajouter</span>';
        }
    });

document
    .getElementById("editBranchForm")
    .addEventListener("submit", async function (e) {
        e.preventDefault();

        const branchId = document.getElementById("editBranchId").value;
        const branchName = document.getElementById("editBranchName").value;
        const errorDiv = document.getElementById("editBranchError");
        const submitBtn = document.getElementById("editBranchSubmitBtn");

        if (!branchName) {
            errorDiv.textContent = "Le nom de la branche est obligatoire.";
            errorDiv.style.display = "block";
            return;
        }

        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="btn-spinner"></span>';
        errorDiv.style.display = "none";

        try {
            await apiRequest("/branches/update", {
                method: "POST",
                body: JSON.stringify({
                    branch_id: branchId,
                    branche_name: branchName,
                }),
            });

            showToast("Branche modifiée avec succès");
            closeEditBranchModal();
            loadBranches();
            loadTabs();
        } catch (error) {
            let errorMessage = "Erreur lors de la modification de la branche";
            if (
                error.response &&
                error.response.data &&
                error.response.data.error
            ) {
                errorMessage = error.response.data.error;
            } else if (error.message) {
                errorMessage = error.message;
            }
            errorDiv.textContent = errorMessage;
            errorDiv.style.display = "block";
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<span class="btn-text">Enregistrer</span>';
        }
    });

document.getElementById("branchModal").addEventListener("click", function (e) {
    if (e.target === this) closeBranchModal();
});

document
    .getElementById("confirmEraseBranchModal")
    .addEventListener("click", function (e) {
        if (e.target === this) closeConfirmEraseBranchModal();
    });

document
    .getElementById("addBranchModal")
    .addEventListener("click", function (e) {
        if (e.target === this) closeAddBranchModal();
    });

document
    .getElementById("editBranchModal")
    .addEventListener("click", function (e) {
        if (e.target === this) closeEditBranchModal();
    });

document.addEventListener("DOMContentLoaded", function () {
    initPage();
});
