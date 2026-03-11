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
            <!-- Head Title -->
            <x-super_admin.search.head-title :query="$query" />
            <!-- Products Results -->
            <x-super_admin.search.products.products-results :results="$results" />
            <!-- Users Results -->
            <x-super_admin.search.users.users-results :results="$results" />
            <!-- Categories Results -->
            <x-super_admin.search.categories.categories-results :results="$results" />
            <!-- Branches Results -->
            <x-super_admin.search.branches.branches-results :results="$results" />
            <!-- No results -->
            <x-super_admin.search.no-results :query="$query" :results="$results" />

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Product Modal -->
    <x-super_admin.search.products.products-modal />
    <!-- User Modal -->
    <x-super_admin.search.users.users-modal />
    <!-- Category Modal -->
    <x-super_admin.search.categories.categories-modal />
    <!-- Category Edit Modal -->
    <x-super_admin.search.categories.categories-edit-modal />
    <!-- Branch Modal -->
    <x-super_admin.search.branches.branches-modal />
    <!-- Confirm Erase Modal -->
    <x-super_admin.search.confirm-erase-modal />
    <!-- Footer -->
    <x-super_admin.search.search-footer />
</body>

</html>
