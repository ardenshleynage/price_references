<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app')]
class ResetPassword extends Component
{
    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $token = '';

    public $successMessage = '';

    protected $rules = [
        'token' => 'required',
        'email' => 'required|email|ends_with:gmail.com',
        'password' => 'required|confirmed|min:4|max:255',

    ];

    protected function messages(): array
    {
        return [
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email doit être valide.",
            'email.ends_with' => "L'adresse email doit être une adresse Gmail (@gmail.com).",
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
            'password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
            'token.required' => 'Le token de réinitialisation est invalide.',
        ];
    }

    public function mount($token = null)
    {
        $this->token = $token;
    }

    public function resetPassword()
    {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user, $password) {
                $user->password = bcrypt($password);
                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            $this->successMessage = 'Mot de passe réinitialisé. Vous pouvez maintenant vous connecter.';
        } else {
            $this->addError('email', 'Token invalide ou email incorrect.');
        }
    }

    public function render()
    {
        return view('livewire.auth.reset-password');
    }
}
