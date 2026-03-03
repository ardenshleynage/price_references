<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-sidebar />
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
                            <a class="active" href="{{ route('admins_home') }}">Home</a>
                        </li>
                    </ul>
                </div>
                <!-- <a href="https://codepen.io/saglik216/pen/LEVjwBV" class="btn-download" target="_blink"> -->
                <!--     <i class='bx bxs-cloud-download bx-fade-down-hover'></i> -->
                <!--     <span class="text">Ajoutez un produit</span> -->
                <!-- </a> -->
                <!-- <a href="https://codepen.io/saglik216/pen/LEVjwBV" class="btn-download" target="_blink"> -->
                <!--     <i class='bx bxs-cloud-download bx-fade-down-hover'></i> -->
                <!--     <span class="text">Ajoutez une catégorie</span> -->
                <!-- </a> -->

            </div>

            <ul class="box-info">
                <li>
                    <i class='bx bxs-calendar-check'></i>
                    <span class="text">
                        <h3>1020</h3>
                        <p>Produits</p>
                    </span>
                </li>
                <li>
                    <i class='bx bxs-group'></i>
                    <span class="text">
                        <h3>2834</h3>
                        <p>Catégories</p>
                    </span>
                </li>
                <!-- <li> -->
                <!--     <i class='bx bxs-dollar-circle'></i> -->
                <!--     <span class="text"> -->
                <!--         <h3>N$2543.00</h3> -->
                <!--         <p>Total Sales</p> -->
                <!--     </span> -->
                <!-- </li> -->
            </ul>

            <!-- <div class="container"> -->
            <!--     <div class="tabs"> -->
            <!--         <input type="radio" id="radio-1" name="tabs" checked /> -->
            <!--         <label class="tab" for="radio-1">Upcoming<span class="notification">2</span></label> -->
            <!--         <input type="radio" id="radio-2" name="tabs" /> -->
            <!--         <label class="tab" for="radio-2">Development</label> -->
            <!--         <input type="radio" id="radio-3" name="tabs" /> -->
            <!--         <label class="tab" for="radio-3">Completed</label> -->
            <!--         <span class="glider"></span> -->
            <!--     </div> -->
            <!-- </div> -->
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    @vite(['resources/js/script.js'])
</body>

</html>
