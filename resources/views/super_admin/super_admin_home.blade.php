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
            <div class="head-title">
                <div class="left">
                    <h1>Dashboard</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Dashboard</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="{{ route('super_admin_home') }}">Home</a>
                        </li>
                    </ul>
                </div>
            </div>

            <ul class="box-info">
                <li>
                    <a href="{{ route('super_admin_products') }}">
                        <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                        <span class="text">
                            <h3>{{ $totalProducts }}</h3>
                            <p>Produits</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super_admin_users') }}">
                        <i class='bx bxs-group bx-sm'></i>
                        <span class="text">
                            <h3>{{ $totalUsers }}</h3>
                            <p>Utilisateurs</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super_admin_categories') }}">
                        <i class='bx bxs-category'></i>
                        <span class="text">
                            <h3>{{ $totalCategories }}</h3>
                            <p>Catégories</p>
                        </span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('super_admin_branches') }}">
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
