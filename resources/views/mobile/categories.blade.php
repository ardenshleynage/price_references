<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <!-- Navbar -->
    <x-mobile.navbar />
    <!-- categories Main Content -->
    <x-mobile.category.main-content />
    <!-- Bottom Nav -->
    <x-mobile.bottom-nav />
    <!-- Category Modal -->
    <x-mobile.category.category-modal />
    <!-- Confirm Erase Modal -->
    <x-mobile.category.confirm-erase-modal />
    <!-- Add Category Modal -->
    <x-mobile.category.add-category-modal />
    <!-- Edit Category Modal -->
    <x-mobile.category.edit-category-modal />
    <!-- Footer/Js -->
    <x-mobile.category.category-footer />
</body>

</html>
