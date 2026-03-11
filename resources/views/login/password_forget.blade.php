<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mot de passe oublié</title>
    @vite(['resources/css/login.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <a class="back_button" href="{{ route('login') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Mot de passe oublié</h2>

        <form class="login-container" method="POST" action="{{ route('password.email') }}">
            @csrf
            <p><input type="email" name="email" placeholder="Entrez votre adresse email (@gmail.com)" required></p>
            <p><input type="submit" value="Envoyer le lien de réinitialisation"></p>
        </form>

        @if(session('status'))
            <div class="alert-success-message" style="margin-top: 20px; text-align: center;">
                {{ session('status') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert-error-message" style="margin-top: 20px; text-align: center;">
                {{ $errors->first() }}
            </div>
        @endif
    </div>
</body>

</html>
