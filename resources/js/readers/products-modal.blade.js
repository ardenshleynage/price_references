document.addEventListener("DOMContentLoaded", function () {
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
        document.getElementById("modalProductName").textContent = product_name;
        document.getElementById("modalPostScriptum").textContent = post_scriptum || 'Aucun';
        document.getElementById("modalSinglePrice").textContent = single_price;
        document.getElementById("modalDetailedPrice").textContent = detailed_price || 'Aucun';
        document.getElementById("modalBranchName").textContent = branchName || 'Aucun';
        document.getElementById("modalCategoryName").textContent = categoryName || 'Aucun';
        document.getElementById("productModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };

    window.closeProductModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("productModal").classList.remove("active");
        document.body.style.overflow = "";
    };

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeProductModal();
        }
    });
});
