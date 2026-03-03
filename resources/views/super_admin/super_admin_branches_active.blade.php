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
            <x-branches.head-title />
            <x-branches.branches-tabs />
            <x-branches.branches-table :branches="$branches" empty-message="Aucune branche active enregistré" />
        </main>
        <x-branches.branches-modal />
        <x-branches.branches-edit-modal />
        <x-branches.alert-messages-branches />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-branches.modal-add-branches :super-admin-exists="$superAdminExists" />

    <x-branches.branches-footer />

</body>

</html>
