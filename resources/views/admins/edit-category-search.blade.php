<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une catégorie</title>
    @vite(['resources/css/login.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <script>
        window.userTheme = "{{ session('theme', 'light') }}";
        if (window.userTheme === "dark") {
            document.documentElement.classList.add("dark");
        }
    </script>
</head>

<body>
    <a class="back_button" href="{{ $q ? url('/admins_search?q=' . $q) : url('/admins_search') }}">Retour</a>

    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Modifier une catégorie</h2>

        <form method="POST" action="{{ route('admins.categories.update_from_search') }}" class="login-container">
            @csrf
            <input type="hidden" name="category_id" value="{{ $category->id }}">
            <input type="hidden" name="q" value="{{ $q }}">
            <p><input type="text" name="category_name" placeholder="Nom de la catégorie"
                    value="{{ $category->category_name }}" required></p>
            <p><input type="submit" value="Enregistrer"></p>
        </form>
        <div class="error-messages">

            @if (session('success'))
                <div class="alert-success-message">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->has('error'))
                <div class="alert-error-message">
                    {{ $errors->first('error') }}
                </div>
            @endif

            @if ($errors->has('category_name'))
                <div class="alert-error-message">
                    {{ $errors->first('category_name') }}
                </div>
            @endif

        </div>

    </div>
</body>

</html>
