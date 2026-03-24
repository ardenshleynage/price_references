<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}">
    <!-- Navbar -->
    <x-mobile.navbar />

    <!-- Main Content -->
    <x-mobile.mobile-dashboard />

    <!-- Bottom Navigation -->
    <x-mobile.bottom-nav />

    <!-- script -->
    <x-mobile.footer />

</body>

</html>
