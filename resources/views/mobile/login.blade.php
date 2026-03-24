<!DOCTYPE html>
<html lang="fr">

<x-mobile.header-login />

<body data-api-url="{{ config('app.url') }}/api" data-login-url="{{ route('mobile.login') }}"
    data-dashboard-url="{{ route('mobile.dashboard') }}">
    <div class="login-page">
        <div class="login-logo">
            <img src="{{ asset('images/bx--bxs-smile.png') }}" alt="Logo">
            <h1>Price References</h1>
            <p>Application Mobile</p>
        </div>

        <div class="login-card">
            <h2>Connexion</h2>

            <div class="error-message" id="errorMessage"></div>

            <form id="loginForm">
                <div class="form-group">
                    <label class="form-label">Nom d'utilisateur</label>
                    <input type="text" name="username" class="form-input"
                        placeholder="Entrez votre nom d'utilisateur" required>
                </div>

                <div class="form-group">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="password" class="form-input" placeholder="Entrez votre mot de passe"
                        required>
                </div>

                <button type="submit" class="btn btn-primary">
                    <i class='bx bxs-log-in'></i>
                    Se connecter
                </button>
            </form>
        </div>

        <div class="login-footer">
            <p>Version 1.0.0</p>
        </div>
    </div>

    <!-- script -->
    <x-mobile.footer />

</body>

</html>
