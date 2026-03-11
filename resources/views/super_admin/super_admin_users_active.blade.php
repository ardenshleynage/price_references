<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-super_admin.super-adim-sidebar />
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <x-navbar />
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <x-super_admin.users.head-title />
            <x-super_admin.users.super_admin_tab_users />
            <x-super_admin.users.users-table :users="$users" empty-message="Aucun utilisateur actif enregistré" />
        </main>
        <x-super_admin.users.user-modal />
        <x-super_admin.users.users-edit-modal />
        <x-super_admin.users.alert-messages-users />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-super_admin.users.modal_add_users :super-admin-exists="$superAdminExists" />

    <x-super_admin.users.footer />

</body>

</html>
