document.addEventListener("DOMContentLoaded", function () {
    window.openBranchModal = function (id, brancheName, status, createdAt, updatedAt) {
        document.getElementById("modalBrancheName").textContent = brancheName;
        document.getElementById("branchesModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };

    window.closeBranchModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("branchesModal").classList.remove("active");
        document.body.style.overflow = "";
    };

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeBranchModal();
        }
    });
});
