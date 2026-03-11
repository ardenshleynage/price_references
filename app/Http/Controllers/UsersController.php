<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    //
    public function loginUsers(Request $request): RedirectResponse
    {
        // Validation des champs
        $request->validate([
            'username' => 'required|string|max:255|min:2',
            'password' => 'required|string|max:255|min:4',
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
        ]);
        try {
            // Récupérer l'utilisateur par username
            $user = User::where('username', $request->username)->first();
            // Vérifier que l'utilisateur existe
            if (! $user) {
                return back()->withErrors(['username' => "Nom d'utilisateur ou mot de passe incorrect."]);
            }
            // Vérifier le status de l'utilisateur
            if ($user->status === 0) {
                return back()->withErrors(['username' => 'Ce compte a été supprimé.']);
            }
            if ($user->status === 2) {
                return back()->withErrors(['username' => "Ce compte est bloqué. Contactez l'administrateur."]);
            }
            // Vérifier le mot de passe
            if (! Hash::check($request->password, $user->password)) {
                return back()->withErrors(['password' => "Nom d'utilisateur ou mot de passe incorrect."]);
            }
            // Vérifier si "se souvenir de moi" est coché
            $remember = $request->filled('remember');

            // Connexion avec Auth (gère automatiquement le remember token et cookie)
            Auth::login($user, $remember);
            // Mettre à jour la dernière connexion
            $user->update(['last_time_connect' => now()]);
            // Créer la session
            $request->session()->put('user_id', $user->id);
            /* $request->session()->put('user_role', $user->role); */
            $request->session()->put('role', $user->role);
            $request->session()->put('theme', $user->theme ?? 'light');
            // Redirection selon le rôle
            switch ($user->role) {
                case 1: // Super Admin
                    return redirect()->route('super_admin_home');
                case 2: // Admin
                    return redirect()->route('admins_home');
                case 3: // Utilisateur
                    return redirect()->route('readers_home');
                default:
                    return back()->withErrors(['error' => 'Rôle non reconnu.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function updateThemeFromProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|string|in:light,dark',
        ]);

        try {
            $user = User::findOrFail(session('user_id'));
            $user->theme = $request->theme;
            $user->save();
            session()->put('theme', $request->theme);

            return back()->with('success', 'Thème modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage());
        }
    }

    public function updateUsername(Request $request): RedirectResponse
    {
        $id = session('user_id');
        $request->validate([
            'username' => 'required|string|max:255|min:2|unique:users,username,'.$id,
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.unique' => "Ce nom d'utilisateur existe déjà.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",

        ]);

        try {
            $user = User::findOrFail($id);
            $user->username = $request->username;
            $user->save();

            return back()->with('success', 'Nom d\'utilisateur modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage());
        }
    }

    public function updateEmail(Request $request): RedirectResponse
    {
        $id = session('user_id');
        $request->validate([
            'email' => 'required|string|email|max:255|unique:users,email,'.$id.'|ends_with:gmail.com',
        ], [
            'email.required' => "L'adresse email est obligatoire.",
            'email.email' => "L'adresse email doit être valide.",
            'email.max' => "L'adresse email doit contenir au maximum 255 caractères.",
            'email.unique' => 'Cette adresse email existe déjà.',
            'email.ends_with' => "L'adresse email doit être une adresse Gmail (@gmail.com).",
        ]);

        try {
            $user = User::findOrFail($id);
            $user->email = $request->email;
            $user->save();

            return back()->with('success', 'Adresse email modifiée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage());
        }
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:4|max:255',
            'confirm_password' => 'required|string|same:new_password',
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'new_password.required' => 'Le nouveau mot de passe est obligatoire.',
            'new_password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
            'new_password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
            'confirm_password.same' => 'Les mots de passe ne correspondent pas.',
        ]);

        try {
            $user = User::findOrFail(session('user_id'));

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Mot de passe modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : '.$e->getMessage());
        }
    }

    public function updateSidebarState(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'collapsed' => 'required|boolean',
            ]);
            $user = User::find(session('user_id'));
            if ($user) {
                $user->sidebar_collapsed = $request->collapsed;
                $user->save();
                session()->put('sidebar_collapsed', $request->collapsed);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function logout(): RedirectResponse
    {
        // Déconnecter l'utilisateur (y compris le remember token)
        Auth::logout();

        // Effacer toutes les données de session
        session()->flush();

        return redirect()->route('login');
    }
}
