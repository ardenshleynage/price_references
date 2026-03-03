<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une branche</title>
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
    <a class="back_button" href="{{ url()->previous() }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Modifier une branche</h2>

        <form method="POST" action="{{ route('branches.update_from_search') }}" class="login-container">
            @csrf
            <input type="hidden" name="branche_id" value="{{ $branche->id }}">
            <input type="hidden" name="q" value="{{ $q }}">
            <p><input type="text" name="branche_name" placeholder="Nom de la branche" value="{{ $branche->branche_name }}" required></p>
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

            @if ($errors->has('branche_name'))
                <div class="alert-error-message">
                    {{ $errors->first('branche_name') }}
                </div>
            @endif

        </div>

    </div>
</body>

</html>
