<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Boxicons -->
    <!-- <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'> -->
    <!-- <link href='https://unpkg.com/boxicons@2.1.4/dist/boxicons.js' rel='stylesheet'> -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.userTheme = "{{ session('theme', 'light') }}";
        if (window.userTheme === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
    <script>
        // Appliquer l'état du sidebar immédiatement depuis localStorage
        (function() {
            var savedState = localStorage.getItem("sidebarCollapsed");
            if (savedState === "true") {
                document.documentElement.classList.add("sidebar-collapsed");
            }
        })();
    </script>
    <style>
        /* CSS critique pour éviter le flash - load immédiatement */
        html.sidebar-collapsed #sidebar { width: 60px !important; }
        html.sidebar-collapsed #sidebar .text { display: none !important; }
        html.sidebar-collapsed #sidebar .brand .text { display: none !important; }
        html.sidebar-collapsed #content { width: calc(100% - 60px) !important; left: 60px !important; }
    </style>

    <!-- My CSS -->
    @vite(['resources/css/styles.css', 'resources/scss/tabs.scss', 'resources/css/form.css'])

    <title>AdminHub</title>
</head>
{{ $slot }}
