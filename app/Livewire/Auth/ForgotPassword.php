<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('app')]
class ForgotPassword extends Component
{
    public $email = '';

    public $successMessage = '';

    protected $rules = [
        'email' => 'required|email',
    ];

    public function sendResetLink()
    {
        $this->validate();

        $status = Password::sendResetLink(['email' => $this->email]);

        if ($status === Password::RESET_LINK_SENT) {
            $this->successMessage = 'Un lien de réinitialisation vous a été envoyé.';
            $this->email = '';
        } else {
            $this->addError('email', 'Email introuvable.');
        }
    }

    public function render()
    {
        return view('livewire.auth.forgot-password');
    }
}
