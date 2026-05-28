<div class="login">
    <div class="login-triangle"></div>
    <h2 class="login-header">Réinitialisation du mot de passe</h2>

    <form wire:submit.prevent="resetPassword" class="login-container">
        @csrf

        @if ($successMessage)
            <div style="background-color: #d4edda; color: #155724; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ $successMessage }}
            </div>
        @endif

        <p><input wire:model="email" type="email" placeholder="Votre adresse email"></p>
        @error('email')
            <div style="color: #721c24; background-color: #f8d7da; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
        @enderror

        <p><input wire:model="password" type="password" placeholder="Nouveau mot de passe"></p>
        @error('password')
            <div style="color: #721c24; background-color: #f8d7da; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
        @enderror

        <p><input wire:model="password_confirmation" type="password" placeholder="Confirmer le mot de passe"></p>

        <p><input type="submit" value="Réinitialiser"></p>
        <p><a href="{{ route('login') }}">Retour à la connexion</a></p>
    </form>
</div>
