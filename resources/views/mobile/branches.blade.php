<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <!-- Navbar -->
    <x-mobile.navbar />
    <!-- branches Main Content -->
    <x-mobile.branch.main-content />
    <!-- Bottom Nav -->
    <x-mobile.bottom-nav />
    <!-- Branch Modal -->
    <x-mobile.branch.branch-modal />
    <!-- Confirm Erase Modal -->
    <x-mobile.branch.confirm-erase-modal />
    <!-- Add Branch Modal -->
    <x-mobile.branch.add-branch-modal />
    <!-- Edit Branch Modal -->
    <x-mobile.branch.edit-branch-modal />
    <!-- Footer/Js -->
    <x-mobile.branch.branch-footer />
</body>

</html>
