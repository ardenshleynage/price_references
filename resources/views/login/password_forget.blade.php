<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mots de passe oublier</title>
    @vite(['resources/css/login.css'])

</head>

<body>

    <a class="back_button" href="{{ route('login') }}">Retour</a>
    <div class="login">
        <div class="login-triangle"></div>

        <h2 class="login-header">Mots de passe oublier</h2>

        <form class="login-container">
            <p><input type="text" name="username" placeholder="Entrez votre nom d'utilisateur"></p>
            <p> <a href="{{ route('contact_it') }}">Nom d'utilisateur oublier ?</a></p>
            <p><input type="submit" value="Connexion"></p>
        </form>
    </div>
</body>

</html>
