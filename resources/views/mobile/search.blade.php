<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">
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
    <!-- Footer/JS -->
    <x-mobile.search.search-footer />
</body>

</html>
