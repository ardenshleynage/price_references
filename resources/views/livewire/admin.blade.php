<x-header />

<body>
    <x-sidebar />

    <section id="content">
        <x-navbar />

        <main>
            <div class="head-title">
                <div class="left">
                    <h1>{{ $title ?? 'Dashboard' }}</h1>
                    <ul class="breadcrumb">
                        <li><a href="#">Dashboard</a></li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li><a class="active">{{ $current ?? '' }}</a></li>
                    </ul>
                </div>
                @php
                    use Illuminate\Support\Facades\Auth;
                    $routeName = request()->route()->getName();
                    $page = preg_replace('/^(super_admin|admins|readers)_/', '', $routeName);
                    $btnTexts = [
                        'categories' => 'Ajoutez une catégorie',
                        'users' => 'Ajoutez un Admin/Lecteur',
                        'branches' => 'Ajoutez une branche',
                        'products' => 'Ajoutez un produit',
                    ];
                    $showAddButton = isset($btnTexts[$page]);
                    if ($showAddButton) {
                        $role = (int) Auth::user()->role;
                        if ($page === 'users') {
                            $showAddButton = $role === 1;
                        } else {
                            $showAddButton = $role <= 2;
                        }
                    }
                @endphp
                @if ($showAddButton)
                    <a href="#" class="btn-download" x-data @click="$dispatch('open-add-modal')">
                        <i class='bx bxs-cloud-download bx-fade-down-hover'></i>
                        <span class="text">{{ $btnTexts[$page] }}</span>
                    </a>
                @endif
            </div>
            {{ $slot }}
        </main>
    </section>

    @vite(['resources/js/app.js'])
    @livewireScripts

    <script>
        document.addEventListener('theme-updated', function (event) {
            const isDark = event.detail.theme === 'dark';
            document.documentElement.classList.toggle('dark', isDark);
            window.userTheme = event.detail.theme;
        });
    </script>
</body>

</html>
