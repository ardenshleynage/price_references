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
            <x-categories.head-title />
            <x-categories.categories-tabs />
            <x-categories.categories-table :categories="$categories" empty-message="Aucune catégorie enregistré" />
        </main>
        <x-categories.categories-modal />
        <x-categories.categories-edit-modal />
        <x-categories.alert-messages-categories />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-categories.modal-add-categories :super-admin-exists="$superAdminExists" />

    <x-categories.catgories-footer />

</body>

</html>
