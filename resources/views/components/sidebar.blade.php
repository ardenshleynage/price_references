    <section id="sidebar">
        <a href="{{ route('admins_home') }}" class="brand">
            <i class='bx bxs-smile  bx-lg'></i>
            <span class="text">AdminHub</span>
        </a>
        <ul class="side-menu top">
            <li class="{{ request()->routeIs('admins_home') ? 'active' : '' }}">
                <a href="{{ route('admins_home') }}">
                    <i class='bx bxs-dashboard bx-sm'></i>
                    <span class="text">Dashboard</span>
                </a>
            </li>
            <li>
                <a href="#">
                    <i class='bx bxs-shopping-bag-alt bx-sm'></i>
                    <span class="text">Produits</span>
                </a>
            </li>
            <li class="{{ request()->routeIs('admins_categories') ? 'active' : '' }}">
                <a href="{{ route('admins_categories') }}">
                    <i class='bx bxs-doughnut-chart bx-sm'></i>
                    <span class="text">Catégories</span>
                </a>
            </li>
            <!-- <li> -->
            <!--     <a href="#"> -->
            <!--         <i class='bx bxs-message-dots bx-sm'></i> -->
            <!--         <span class="text">Message</span> -->
            <!--     </a> -->
            <!-- </li> -->
            <!-- <li> -->
            <!--     <a href="#"> -->
            <!--         <i class='bx bxs-group bx-sm'></i> -->
            <!--         <span class="text">Team</span> -->
            <!--     </a> -->
            <!-- </li> -->
        </ul>
        <ul class="side-menu bottom">
            <!-- <li> -->
            <!--     <a href="#"> -->
            <!--         <i class='bx bxs-cog bx-sm bx-spin-hover'></i> -->
            <!--         <span class="text">Settings</span> -->
            <!--     </a> -->
            <!-- </li> -->
            <li>
                <a href="#" class="logout">
                    <i class='bx bx-power-off bx-sm bx-burst-hover'></i>
                    <span class="text">Déconnexion</span>
                </a>
            </li>
        </ul>
    </section>
    {{ $slot }}
