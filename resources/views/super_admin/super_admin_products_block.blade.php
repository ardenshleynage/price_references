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
            <x-super_admin.products.head-title />
            <x-super_admin.products.products-tabs />
            <x-super_admin.products.products-table :products="$products" empty-message="Aucun produit bloqué enregistré" />
        </main>
        <x-super_admin.products.products-modal />
        <x-super_admin.products.products-edit-modal :branches="$branches" :categories="$categories" />
        <x-super_admin.products.alert-messages-products />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-super_admin.products.modal-add-products :super-admin-exists="$superAdminExists" :branches="$branches" :categories="$categories" />

    <x-super_admin.products.products-footer />

</body>

</html>
