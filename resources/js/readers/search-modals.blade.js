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
    document.getElementById("searchModalProductName").textContent = product_name;
    document.getElementById("searchModalPostScriptum").textContent = post_scriptum || 'Aucun';
    document.getElementById("searchModalSinglePrice").textContent = single_price;
    document.getElementById("searchModalDetailedPrice").textContent = detailed_price || 'Aucun';
    document.getElementById("searchModalBranchName").textContent = branchName || 'Aucun';
    document.getElementById("searchModalCategoryName").textContent = categoryName || 'Aucun';
    document.getElementById("searchProductModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchProductModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchProductModal").classList.remove("active");
    document.body.style.overflow = "";
};

// Category Modal Functions
window.openCategoryModal = function (id, categoryName, status, createdAt, updatedAt) {
    document.getElementById("searchModalCategoryItemName").textContent = categoryName;
    document.getElementById("searchCategoryModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchCategoryModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchCategoryModal").classList.remove("active");
    document.body.style.overflow = "";
};

// Branch Modal Functions
window.openBranchModal = function (id, branchName, status, createdAt, updatedAt) {
    document.getElementById("searchModalBranchItemName").textContent = branchName;
    document.getElementById("searchBranchModal").classList.add("active");
    document.body.style.overflow = "hidden";
};

window.closeSearchBranchModal = function (event) {
    if (event && event.target !== event.currentTarget) return;
    document.getElementById("searchBranchModal").classList.remove("active");
    document.body.style.overflow = "";
};

document.addEventListener("keydown", function (e) {
    if (e.key === "Escape") {
        closeSearchProductModal();
        closeSearchCategoryModal();
        closeSearchBranchModal();
    }
});
