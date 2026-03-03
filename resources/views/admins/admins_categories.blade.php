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
                            <a class="active" href="{{ route('admins_categories') }}">catégories</a>
                        </li>
                    </ul>
                </div>
                <!-- <a href="https://codepen.io/saglik216/pen/LEVjwBV" class="btn-download" target="_blink"> -->
                <!--     <i class='bx bxs-cloud-download bx-fade-down-hover'></i> -->
                <!--     <span class="text">Ajoutez un produit</span> -->
                <!-- </a> -->
                <a href="#" class="btn-download" onclick="openModal(event)">
                    <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
                    <span class="text">Ajoutez une catégorie</span>
                </a>

            </div>


            <div class="container">
                <div class="tabs">
                    <input type="radio" id="radio-1" name="tabs" checked />
                    <label class="tab" for="radio-1">Tous</label>
                    <input type="radio" id="radio-2" name="tabs" />
                    <label class="tab" for="radio-2">Disponible</label>
                    <input type="radio" id="radio-3" name="tabs" />
                    <label class="tab" for="radio-3">Indisponible</label>
                    <input type="radio" id="radio-4" name="tabs" />
                    <label class="tab" for="radio-4">Corbeille</label>

                    <span class="glider"></span>
                </div>
            </div>


            <div class="table-data">
                <div class="order">
                    <table>
                        <thead>
                            <tr>
                                <th>Nom</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <p>Micheal John</p>
                                </td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Ryan Doe</p>
                                </td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Tarry White</p>
                                </td>
                                <td><span class="status process">Process</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Selma</p>
                                </td>
                                <td><span class="status pending">Pending</span></td>
                            </tr>
                            <tr>
                                <td>
                                    <p>Andreas Doe</p>
                                </td>
                                <td><span class="status completed">Completed</span></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <!-- Modal Overlay -->
    <div id="modalOverlay" class="modal-overlay" onclick="closeModal(event)">
        <div class="login modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="closeModal()" aria-label="Fermer">&times;</button>
            <div class="login-triangle"></div>

            <h2 class="login-header">Nouvelle Catégorie</h2>

            <form class="login-container">
                <p><input type="text" placeholder="Nom de la catégorie" required></p>
                <p><input type="submit" value="Ajouter"></p>
            </form>
        </div>
    </div>

    @vite(['resources/js/script.js'])

    <script>
        // Ouvrir la modal
        function openModal(event) {
            if (event) event.preventDefault();
            document.getElementById('modalOverlay').classList.add('active');
            document.body.style.overflow = 'hidden'; // Empêche le scroll
        }

        // Fermer la modal
        function closeModal(event) {
            if (event && event.target !== event.currentTarget) return;
            document.getElementById('modalOverlay').classList.remove('active');
            document.body.style.overflow = ''; // Réactive le scroll
        }

        // Fermer avec la touche ESC
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>

</html>
