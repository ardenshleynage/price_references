<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class Appcontroller extends Controller
{
    //
    public function Login(): View
    {
        return view('login.login');
    }

    public function forgetPassword(): View
    {
        return view('login.password_forget');
    }

    public function sendResetLinkEmail(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email|ends_with:gmail.com|exists:users,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.ends_with' => 'L\'adresse email doit être une adresse Gmail (@gmail.com).',
            'email.exists' => 'Aucun compte trouvé avec cette adresse email.',
        ]);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with(['status' => 'Un lien de réinitialisation a été envoyé à votre adresse email.']);
        }

        return back()->withErrors(['email' => 'Impossible d\'envoyer le lien de réinitialisation.']);
    }

    public function showResetForm(Request $request, string $token): View
    {
        return view('login.new_password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|ends_with:gmail.com',
            'password' => 'required|confirmed|min:4|max:255',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.ends_with' => 'L\'adresse email doit être une adresse Gmail (@gmail.com).',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
            'password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function (User $user, string $password) {
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60));

                $user->save();
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Mot de passe réinitialisé avec succès. Veuillez vous connecter.');
        }

        return back()->withErrors(['email' => [__($status)]]);
    }

    public function contactIt(): View
    {
        return view('login.contact_it');
    }

    public function index(): View
    {
        return view('index');
    }
}
