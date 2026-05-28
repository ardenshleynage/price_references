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
