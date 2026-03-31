<!DOCTYPE html>
<html lang="fr">

<x-mobile.header />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">

    <x-mobile.pull-to-refresh />

    <x-mobile.navbar />

    <x-mobile.profile.main-content />

    <x-mobile.bottom-nav />

    <x-mobile.profile.logout-modal />
    <x-mobile.profile.edit-username-modal />
    <x-mobile.profile.edit-email-modal />
    <x-mobile.profile.change-password-modal />

    <x-mobile.profile.profile-footer />
</body>

</html>
