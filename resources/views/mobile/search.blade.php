<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">
    <x-mobile.pull-to-refresh />
    <!-- Navbar -->
    <x-mobile.navbar />
    <!-- Main Content -->
    <x-mobile.search.main-content />
    <!-- Bottom Navbar -->
    <x-mobile.bottom-nav />
    <!-- Product Detail Modal -->
    <x-mobile.search.products.product-detail-modal />
    <!-- Confirm Erase Modal -->
    <x-mobile.search.products.confirm-erase-modal />
    <!-- Edit Product Modal -->
    <x-mobile.search.products.edit-product-modal />
    <!-- Category Detail Modal -->
    <x-mobile.search.categories.category-detail-modal />
    <!-- Confirm Erase Category Modal -->
    <x-mobile.search.categories.confirm-erase-modal />
    <!-- Edit Category Modal -->
    <x-mobile.search.categories.edit-category-modal />
    <!-- Branch Detail Modal -->
    <x-mobile.search.branches.branch-detail-modal />
    <!-- Confirm Erase Branch Modal -->
    <x-mobile.search.branches.confirm-erase-modal />
    <!-- Edit Branch Modal -->
    <x-mobile.search.branches.edit-branch-modal />
    <!-- User Detail Modal -->
    <x-mobile.search.users.user-detail-modal />
    <!-- Confirm Erase User Modal -->
    <x-mobile.search.users.confirm-erase-modal />
    <!-- Edit User Modal -->
    <x-mobile.user.edit-user-modal />
    <!-- Footer/JS -->
    <x-mobile.search.search-footer />
</body>

</html>
