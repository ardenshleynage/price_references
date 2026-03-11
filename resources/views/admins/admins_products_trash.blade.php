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
            <x-admins.products.head-title />
            <x-admins.products.products-tabs />
            <x-admins.products.products-table :products="$products" empty-message="Corbeille vide" />
        </main>
        <x-admins.products.products-modal />
        <x-admins.products.products-edit-modal :branches="$branches" :categories="$categories" />
        <x-admins.products.alert-messages-products />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <x-admins.products.modal-add-products :dmins-exists="$adminsExists" :branches="$branches" :categories="$categories" />


    <x-admins.products.products-footer />

</body>

</html>
