<?php

namespace App\Http\Controllers;

use App\Models\EndUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminsController extends Controller
{
    //
    //
    public function AdminsHome(): View
    {
        return view('admins.admins_home');
    }

    public function AdminsCategories(): View
    {
        return view('admins.admins_categories');
    }

    public function loginAdminsAndSuper(Request $request): RedirectResponse
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
            $user = EndUser::where('username', $request->username)->first();
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
            // Mettre à jour la dernière connexion
            $user->update(['last_time_connect' => now()]);
            // Connexion : créer la session
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
                    return redirect()->route('user_dashboard');
                default:
                    return back()->withErrors(['error' => 'Rôle non reconnu.']);
            }
        } catch (\Exception $e) {
            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }

    public function logout(): RedirectResponse
    {
        session()->flush();

        return redirect()->route('home');
    }

    public function updateTheme(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'theme' => 'required|string|in:light,dark',
            ]);

            $user = EndUser::find(session('user_id'));

            if ($user) {
                $user->theme = $request->theme;
                $user->save();
                session()->put('theme', $request->theme);

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function updateSidebarState(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'collapsed' => 'required|boolean',
            ]);
            $user = EndUser::find(session('user_id'));
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
}
