<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nouveau mot de passe</title>
    @vite(['resources/css/login.css'])
    <meta name="csrf-token" content="{{ csrf_token() }}">

</head>

<body>

    <a class="back_button" href="{{ route('login') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Nouveau mot de passe</h2>

        <form class="login-container" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">
            
            <p><input type="password" name="password" placeholder="Nouveau mot de passe" required></p>
            <p><input type="password" name="password_confirmation" placeholder="Confirmer le mot de passe" required></p>
            <p><input type="submit" value="Réinitialiser le mot de passe"></p>
        </form>

        @if($errors->any())
            <div class="error-messages" style="margin-top: 20px; text-align: center;">
                @foreach($errors->all() as $error)
                    <div class="alert-error-message" style="margin-bottom: 10px;">
                        {{ $error }}
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>

</html>
