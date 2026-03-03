<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    @vite(['resources/css/login.css'])

</head>

<body>
    <a class="back_button" href="{{ url('/') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Connexion</h2>

        <form method="POST" action="{{ route('users.login_user') }}" class="login-container">
            @csrf
            <p><input type="text" name="username" placeholder="Nom d'utilisateur"></p>
            <p><input type="password" name="password" placeholder="Mots de passe"></p>
            <p> <a href="{{ route('forget_password') }}">Mots de passe oublier ?</a></p>
            <p><input type="submit" value="Connexion"></p>
        </form>
        <div class="error-messages">

            @if (session('success'))
                <div
                    style="background-color: #d4edda; color: #155724; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb;">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Erreurs générales -->
            @if ($errors->has('error'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                    {{ $errors->first('error') }}
                </div>
            @endif

            <!-- Erreur sur le username -->
            @if ($errors->has('username'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                    {{ $errors->first('username') }}
                </div>
            @endif

            <!-- Erreur sur le password -->
            @if ($errors->has('password'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                    {{ $errors->first('password') }}
                </div>
            @endif

            <!-- Erreur sur le role -->
            @if ($errors->has('role'))
                <div
                    style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                    {{ $errors->first('role') }}
                </div>
            @endif

        </div>

    </div>
</body>

</html>
