<div class="login">
    <div class="login-triangle"></div>
    <h2 class="login-header">Connexion</h2>
    <form wire:submit.prevent="login" class="login-container">
        @csrf
        <p><input wire:model="username" type="text" placeholder="Nom d'utilisateur"></p>
        <p style="position: relative;" x-data="{ showPassword: false, password: '' }"
            x-init="$watch('password', value => $wire.set('password', value))">
            <input x-model="password" x-ref="passwordInput"
                :type="showPassword ? 'text' : 'password'"
                placeholder="Mots de passe" style="padding-right: 40px;">
            <span @click="showPassword = !showPassword; $nextTick(() => $refs.passwordInput.focus())"
                style="position: absolute; right: 16px; top: 50%; transform: translateY(-50%); cursor: pointer; font-size: 20px; color: #888;">
                <i x-show="!showPassword" class='bx bx-hide'></i>
                <i x-show="showPassword" class='bx bx-show'></i>
            </span>
        </p>
        <p style="display: flex; align-items: center; gap: 8px;">
            <input wire:model="remember" type="checkbox" id="remember" style="width: auto; margin: 0;">
            <label for="remember" style="margin: 0; font-size: 14px;">Se souvenir de moi</label>
        </p>
        <p><a href="{{ route('password.request') }}">Mots de passe oublié ?</a></p>
        <p><input type="submit" value="Connexion"></p>
    </form>
    <div class="error-messages">
        @if (session('success'))
            <div
                style="background-color: #d4edda; color: #155724; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #c3e6cb;">
                {{ session('success') }}
            </div>
        @endif
        @error('username')
            <div
                style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
        @enderror
        @error('password')
            <div
                style="background-color: #f8d7da; color: #721c24; padding: 12px; margin-bottom: 15px; border-radius: 4px; border: 1px solid #f5c6cb;">
                {{ $message }}
            </div>
        @enderror
    </div>
</div>
