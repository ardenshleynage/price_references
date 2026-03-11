<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-readers.readers-sidebar />
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <x-navbar />
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <x-readers.products.head-title />
            <x-readers.products.products-table :products="$products" empty-message="Aucun produit actif enregistré" />
        </main>
        <x-readers.products.products-modal />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <x-readers.products.products-footer />

</body>

</html>
