<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.admin', params: ['current' => 'Profile'])]
#[Title('Mon profil')]
class Index extends Component
{
    public string $username = '';

    public string $email = '';

    public string $current_password = '';

    public string $new_password = '';

    public string $confirm_password = '';

    public string $theme = 'light';

    public function mount(): void
    {
        $user = Auth::user();
        $this->username = $user->username;
        $this->email = $user->email ?? '';
        $this->theme = $user->theme ?? 'light';
    }

    public function updateUsername(): void
    {
        $this->validate([
            'username' => 'required|string|max:255|min:2|unique:users,username,'.Auth::id(),
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'username.unique' => "Ce nom d'utilisateur est déjà pris.",
        ]);

        Auth::user()->update(['username' => $this->username]);
        session()->flash('success', "Nom d'utilisateur modifié avec succès.");
    }

    public function updateEmail(): void
    {
        $this->validate([
            'email' => 'required|string|email|max:255|ends_with:gmail.com|unique:users,email,'.Auth::id(),
        ], [
            'email.required' => "L'adresse email est obligatoire.",
            'email.string' => "L'adresse email doit être une chaîne de caractères.",
            'email.email' => "L'adresse email n'est pas valide.",
            'email.max' => "L'adresse email doit contenir au maximum 255 caractères.",
            'email.ends_with' => "L'adresse email doit se terminer par @gmail.com.",
            'email.unique' => 'Cette adresse email est déjà utilisée.',
        ]);

        Auth::user()->update(['email' => $this->email]);
        session()->flash('success', 'Adresse email modifiée avec succès.');
    }

    public function updatePassword(): void
    {
        $this->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:4|max:255',
            'confirm_password' => 'required|string|same:new_password',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le nouveau mot de passe doit contenir au moins 4 caractères.',
            'new_password.max' => 'Le nouveau mot de passe doit contenir au maximum 255 caractères.',
            'confirm_password.required' => 'La confirmation est obligatoire.',
            'confirm_password.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        $user = Auth::user();

        if (! Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Le mot de passe actuel est incorrect.');

            return;
        }

        $user->update(['password' => Hash::make($this->new_password)]);
        $this->current_password = '';
        $this->new_password = '';
        $this->confirm_password = '';
        session()->flash('success', 'Mot de passe modifié avec succès.');
    }

    public function updateTheme(): void
    {
        $this->validate([
            'theme' => 'required|string|in:light,dark',
        ], [
            'theme.required' => 'Le thème est obligatoire.',
            'theme.in' => 'Le thème doit être "light" ou "dark".',
        ]);

        Auth::user()->update(['theme' => $this->theme]);
        session()->put('theme', $this->theme);
        session()->flash('success', 'Thème modifié avec succès.');

        $this->dispatch('theme-updated', theme: $this->theme);
    }

    public function render()
    {
        return view('livewire.profile.index');
    }
}
