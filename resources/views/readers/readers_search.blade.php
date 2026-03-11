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
            <!-- Head Title -->
            <x-readers.search.head-title :query="$query" />
            <!-- Products Results -->
            <x-readers.search.products.products-results :results="$results" />
            <!-- Categories Results -->
            <x-readers.search.categories.categories-results :results="$results" />
            <!-- Branches Results -->
            <x-readers.search.branches.branches-results :results="$results" />
            <!-- No results -->
            <x-readers.search.no-results :query="$query" :results="$results" />

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Product Modal -->
    <x-readers.search.products.products-modal />
    <!-- Category Modal -->
    <x-readers.search.categories.categories-modal />
    <!-- Branch Modal -->
    <x-readers.search.branches.branches-modal />
    <!-- Footer -->
    <x-readers.search.search-footer />
</body>

</html>
