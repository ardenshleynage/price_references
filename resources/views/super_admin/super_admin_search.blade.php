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
            <!-- Head Title -->
            <x-search.head-title :query="$query" />
            <!-- Products Results -->
            <x-search.products.products-results :results="$results" />
            <!-- Users Results -->
            <x-search.users.users-results :results="$results" />
            <!-- Categories Results -->
            <x-search.categories.categories-results :results="$results" />
            <!-- Branches Results -->
            <x-search.branches.branches-results :results="$results" />
            <!-- No results -->
            <x-search.no-results :query="$query" :results="$results" />

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Product Modal -->
    <x-search.products.products-modal />
    <!-- User Modal -->
    <x-search.users.users-modal />
    <!-- Category Modal -->
    <x-search.categories.categories-modal />
    <!-- Category Edit Modal -->
    <x-search.categories.categories-edit-modal />
    <!-- Branch Modal -->
    <x-search.branches.branches-modal />
    <!-- Confirm Erase Modal -->
    <x-search.confirm-erase-modal />
    <!-- Footer -->
    <x-search.search-footer />
</body>

</html>
