<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-super-adim-sidebar />
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <x-navbar />
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <x-users.head-title />
            <x-users.super_admin_tab_users />
            <x-users.users-table :users="$users" empty-message="Aucun utilisateur enregistré" />
        </main>
        <x-users.user-modal />
        <x-users.users-edit-modal />
        <x-users.alert-messages-users />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-users.modal_add_users :super-admin-exists="$superAdminExists" />

    <x-users.footer />

</body>

</html>
