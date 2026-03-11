document.addEventListener("DOMContentLoaded", function () {
    window.openCategoryModal = function (id, categoryName, status, createdAt, updatedAt) {
        document.getElementById("modalCategoryName").textContent = categoryName;
        document.getElementById("categoryModal").classList.add("active");
        document.body.style.overflow = "hidden";
    };

    window.closeCategoryModal = function (event) {
        if (event && event.target !== event.currentTarget) return;
        document.getElementById("categoryModal").classList.remove("active");
        document.body.style.overflow = "";
    };

    document.addEventListener("keydown", function (e) {
        if (e.key === "Escape") {
            window.closeCategoryModal();
        }
    });
});
