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
            <x-readers.categories.head-title />
            <x-readers.categories.categories-table :categories="$categories" empty-message="Aucune catégorie active enregistrée" />
        </main>
        <x-readers.categories.categories-modal />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <x-readers.categories.categories-footer />

</body>

</html>
