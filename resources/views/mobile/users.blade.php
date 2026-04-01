<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <!-- Navbar -->
    <x-mobile.navbar />
    <!-- Users Main Content -->
    <x-mobile.user.main-content />
    <!-- Bottom Nav -->
    <x-mobile.bottom-nav />
    <!-- User Modal -->
    <x-mobile.user.user-modal />
    <!-- Confirm Erase Modal -->
    <x-mobile.user.confirm-erase-modal />
    <!-- Add User Modal -->
    <x-mobile.user.add-user-modal />
    <!-- Edit User Modal -->
    <x-mobile.user.edit-user-modal />
    <!-- Footer/Js -->
    <x-mobile.user.user-footer />
</body>

</html>
