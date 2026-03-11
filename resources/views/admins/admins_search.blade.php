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
            <!-- Head Title -->
            <x-admins.search.head-title :query="$query" />
            <!-- Products Results -->
            <x-admins.search.products.products-results :results="$results" />
            <!-- Categories Results -->
            <x-admins.search.categories.categories-results :results="$results" />
            <!-- Branches Results -->
            <x-admins.search.branches.branches-results :results="$results" />
            <!-- No results -->
            <x-admins.search.no-results :query="$query" :results="$results" />

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Product Modal -->
    <x-admins.search.products.products-modal />
    <!-- Category Modal -->
    <x-admins.search.categories.categories-modal />
    <!-- Category Edit Modal -->
    <x-admins.search.categories.categories-edit-modal />
    <!-- Branch Modal -->
    <x-admins.search.branches.branches-modal />
    <!-- Confirm Erase Modal -->
    <x-admins.search.confirm-erase-modal />
    <!-- Footer -->
    <x-admins.search.search-footer />
</body>

</html>
