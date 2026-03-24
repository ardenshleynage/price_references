<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <!-- Navbar  -->
    <x-mobile.navbar />
    <!-- Main Content  -->
    <x-mobile.product.main-content />
    <!-- Bottom Navbar -->
    <x-mobile.bottom-nav />
    <!-- Product Modal -->
    <x-mobile.product.product-modal />
    <!-- Confirm Erase Modal -->
    <x-mobile.product.confirm-erase-modal />
    <!-- Add Product Modal -->
    <x-mobile.product.add-product-modal />
    <!-- Edit Product Modal -->
    <x-mobile.product.edit-product-modal />
    <!-- Foote/Js -->
    <x-mobile.product.product-footer />
</body>

</html>
