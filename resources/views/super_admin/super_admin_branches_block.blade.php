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
            <x-super_admin.branches.head-title />
            <x-super_admin.branches.branches-tabs />
            <x-super_admin.branches.branches-table :branches="$branches" empty-message="Aucune branche bloquée enregistré" />
        </main>
        <x-super_admin.branches.branches-modal />
        <x-super_admin.branches.branches-edit-modal />
        <x-super_admin.branches.alert-messages-branches />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-super_admin.branches.modal-add-branches :super-admin-exists="$superAdminExists" />

    <x-super_admin.branches.branches-footer />

</body>

</html>
