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
            <x-super_admin.categories.head-title />
            <x-super_admin.categories.categories-tabs />
            <x-super_admin.categories.categories-table :categories="$categories"
                empty-message="Aucune catégorie bloquée enregistré" />
        </main>
        <x-super_admin.categories.categories-modal />
        <x-super_admin.categories.categories-edit-modal />
        <x-super_admin.categories.alert-messages-categories />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-super_admin.categories.modal-add-categories :super-admin-exists="$superAdminExists" />

    <x-super_admin.categories.catgories-footer />

</body>

</html>
