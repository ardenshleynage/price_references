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
            <x-products.head-title />
            <x-products.products-tabs />
            <x-products.products-table :products="$products" empty-message="Aucun produit bloqué enregistré" />
        </main>
        <x-products.products-modal />
        <x-products.products-edit-modal :branches="$branches" :categories="$categories" />
        <x-products.alert-messages-products />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-products.modal-add-products :super-admin-exists="$superAdminExists" :branches="$branches" :categories="$categories" />

    <x-products.products-footer />

</body>

</html>
