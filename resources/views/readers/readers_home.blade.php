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
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="{{ route('readers_home') }}">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <a href="{{ route('readers_products_active') }}">
                        <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                        <span class="text">
                            <h3>{{ $totalProducts }}</h3>
                            <p>Produits</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('readers_categories_active') }}">
                        <i class='bx bxs-category'></i>
                        <span class="text">
                            <h3>{{ $totalCategories }}</h3>
                            <p>Catégories</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('readers_branches_active') }}">
                        <i class='bx bx-buildings bx-sm'></i>
                        <span class="text">
                            <h3>{{ $totalBranches }}</h3>
                            <p>Branches</p>
                        </span>
                    </a>
                </li>
            </ul>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    @vite(['resources/js/script.js'])
</body>

</html>
