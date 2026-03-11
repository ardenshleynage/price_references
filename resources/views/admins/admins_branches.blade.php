<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-admins.admins-sidebar />
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <x-navbar />
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <x-admins.branches.head-title />
            <x-admins.branches.branches-tabs />
            <x-admins.branches.branches-table :branches="$branches" empty-message="Aucune branche enregistré" />
        </main>
        <x-admins.branches.branches-modal />
        <x-admins.branches.branches-edit-modal />
        <x-admins.branches.alert-messages-branches />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-admins.branches.modal-add-branches :admins-exists="$adminsExists" />

    <x-admins.branches.branches-footer />

</body>

</html>
