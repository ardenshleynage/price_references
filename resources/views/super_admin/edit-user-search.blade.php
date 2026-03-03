<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un utilisateur</title>
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
    <a class="back_button" href="{{ $q ? url('/super_admin_search?q=' . $q) : url('/super_admin_search') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Modifier un utilisateur</h2>

        <form method="POST" action="{{ route('users.update_from_search') }}" class="login-container">
            @csrf
            <input type="hidden" name="user_id" value="{{ $user->id }}">
            <input type="hidden" name="q" value="{{ $q }}">
            <p><input type="text" name="username" placeholder="Nom d'utilisateur" value="{{ $user->username }}" required></p>
            <p>
                <select name="role" required
                    style="box-sizing: border-box; display: block; width: 100%; border-width: 1px; border-style: solid; padding: 16px; outline: 0; font-family: inherit; font-size: 0.95em; background: #fff; border-color: #bbb; color: #555;">
                    <option value="">Sélectionner un rôle</option>
                    <option value="2" {{ $user->role == 2 ? 'selected' : '' }}>Admin</option>
                    <option value="3" {{ $user->role == 3 ? 'selected' : '' }}>Utilisateur</option>
                </select>
            </p>
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

            @if ($errors->has('username'))
                <div class="alert-error-message">
                    {{ $errors->first('username') }}
                </div>
            @endif

            @if ($errors->has('role'))
                <div class="alert-error-message">
                    {{ $errors->first('role') }}
                </div>
            @endif

        </div>

    </div>
</body>

</html>
