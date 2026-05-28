<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" type="image/png" href="{{ asset('images/bx--bxs-smile.png') }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.userTheme = "{{ session('theme', 'light') }}";
        if (window.userTheme === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>

    <style>
        /* Desktop uniquement - ne pas appliquer sur mobile */
        @media (min-width: 769px) {
            html.sidebar-collapsed #sidebar {
                width: 60px !important;
            }

            html.sidebar-collapsed #sidebar .text {
                display: none !important;
            }

            html.sidebar-collapsed #sidebar .brand .text {
                display: none !important;
            }

            html.sidebar-collapsed #content {
                width: calc(100% - 60px) !important;
                left: 60px !important;
            }
        }
    </style>

    <script>
        (function() {
            if (localStorage.getItem("sidebarCollapsed") === "true") {
                document.documentElement.classList.add("sidebar-collapsed");
            }
        })();
    </script>

    <!-- My CSS -->
    @vite(['resources/css/styles.css', 'resources/scss/tabs.scss', 'resources/css/form.css'])

    @livewireStyles

    <title>Price References</title>
</head>
{{ $slot }}
