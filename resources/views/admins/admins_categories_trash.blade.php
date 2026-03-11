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
            <x-admins.categories.head-title />
            <x-admins.categories.categories-tabs />
            <x-admins.categories.categories-table :categories="$categories" empty-message="Corbeille vide" />
        </main>
        <x-admins.categories.categories-modal />
        <x-admins.categories.categories-edit-modal />
        <x-admins.categories.alert-messages-categories />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-admins.categories.modal-add-categories :admins-exists="$adminsExists" />

    <x-admins.categories.catgories-footer />

</body>

</html>
