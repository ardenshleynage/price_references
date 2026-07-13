<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app')]
class Login extends Component
{
    public $username = '';

    public $password = '';

    public $remember = false;

    protected $rules = [
        'username' => 'required|string',
        'password' => 'required|string',
    ];

    public function login()
    {
        $this->validate();
        $user = User::where('username', $this->username)->first();
        if (! $user || ! Hash::check($this->password, $user->password)) {
            $this->addError('username', 'Identifiants incorrects.');

            return;
        }
        if ((int) $user->status === 0) {
            $this->addError('username', 'Compte supprimé. Contactez l\'administrateur.');

            return;
        }
        if ((int) $user->status === 2) {
            $this->addError('username', 'Compte bloqué. Contactez l\'administrateur.');

            return;
        }
        Auth::login($user, $this->remember);
        $user->update(['last_time_connect' => now()]);
        session()->regenerate();
        session()->put('user_id', $user->id);
        session()->put('role', $user->role);
        session()->put('theme', $user->theme ?? 'light');

        // Redirection selon le rôle
        return match ((int) $user->role) {
            1 => redirect('/super_admin/dashboard'),
            2 => redirect('/admins/dashboard'),
            3 => redirect('/readers/dashboard'),
            default => redirect('/'),
        };
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
