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
            <x-readers.branches.head-title />
            <x-readers.branches.branches-table :branches="$branches" empty-message="Aucune branche active enregistrée" />
        </main>
        <x-readers.branches.branches-modal />
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <x-readers.branches.branches-footer />

</body>

</html>
