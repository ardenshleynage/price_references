<!-- Header -->
<x-header />
<!-- Header -->

<body>
    <!-- SIDEBAR -->
    <x-readers.readers-sidebar />
    <!-- SIDEBAR -->

    <!-- CONTENT -->
    <section id="content">
        <!-- NAVBAR -->
        <x-navbar />
        <!-- NAVBAR -->

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h1>Mon profil</h1>
                    <ul class="breadcrumb">
                        <li>
                            <a href="#">Paramètres</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="{{ route('readers_profile') }}">Mon profil</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="profile-container">
                <!-- Modifier le nom d'utilisateur -->
                <div class="profile-card">
                    <h3><i class='bx bxs-user'></i> Nom d'utilisateur</h3>
                    <form action="{{ route('profile.update_username') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="text" name="username"
                                value="{{ old('username', $loggedUser->username ?? '') }}" required
                                placeholder="Nouveau nom d'utilisateur">
                            @error('username')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn-primary">Enregistrer</button>
                    </form>
                </div>

                <!-- Modifier l'adresse email -->
                <div class="profile-card">
                    <h3><i class='bx bxs-envelope'></i> Adresse email</h3>
                    <form action="{{ route('profile.update_email') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="email" name="email" value="{{ old('email', $loggedUser->email ?? '') }}"
                                required placeholder="Nouvelle adresse email (@gmail.com)">
                            @error('email')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn-primary">Enregistrer</button>
                    </form>
                </div>

                <!-- Modifier le mot de passe -->
                <div class="profile-card">
                    <h3><i class='bx bxs-lock-alt'></i> Mot de passe</h3>
                    <form action="{{ route('profile.update_password') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <input type="password" name="current_password" required placeholder="Mot de passe actuel">
                            @error('current_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="new_password" required placeholder="Nouveau mot de passe">
                            @error('new_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="password" name="confirm_password" required
                                placeholder="Confirmer le mot de passe">
                            @error('confirm_password')
                                <span class="error-message">{{ $message }}</span>
                            @enderror
                        </div>
                        <button type="submit" class="btn-primary">Changer le mot de passe</button>
                    </form>
                </div>

                <!-- Changer le thème -->
                <div class="profile-card">
                    <h3><i class='bx bxs-palette'></i> Thème</h3>
                    <form action="{{ route('profile.update_theme') }}" method="POST">
                        @csrf
                        <div class="form-group">
                            <select name="theme" id="theme-select">
                                <option value="light"
                                    {{ ($loggedUser->theme ?? 'light') === 'light' ? 'selected' : '' }}>Mode clair
                                </option>
                                <option value="dark"
                                    {{ ($loggedUser->theme ?? 'light') === 'dark' ? 'selected' : '' }}>Mode sombre
                                </option>
                            </select>
                        </div>
                        <button type="submit" class="btn-primary">Appliquer</button>
                    </form>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">
                    {{ session('error') }}
                </div>
            @endif
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    @vite(['resources/js/script.js'])
</body>

</html>
