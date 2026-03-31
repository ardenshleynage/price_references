<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="theme-color" content="#667eea">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <title>Price References</title>
    <link rel="icon" type="image/png" href="{{ asset('images/bx--bxs-smile.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css" rel="stylesheet">
    <script>
        (function() {
            const theme = localStorage.getItem('mobile_theme') || 'light';
            if (theme === 'dark') {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>
    <style>
        html.dark { --primary: #667eea; --primary-dark: #5a6fd6; --secondary: #764ba2; --success: #10b981; --warning: #f59e0b; --danger: #ef4444; --dark: #f3f4f6; --gray: #9ca3af; --light: #1a1a2e; --white: #1e1e2e; --text-color: #f3f4f6; --bg-color: #121212; --card-bg: #1e1e2e; --border-color: #374151; --nav-bg: #1e1e2e; }
    </style>
    @vite(['resources/css/mobile/styles.css'])
</head>
