<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Models\Branches;
use App\Models\Categories;
use App\Models\EndUser;
use App\Models\Products;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SuperAdminController extends Controller
{
    use DateTimeHelper;

    /**
     * @return array<string, string>
     */
    public function superAdminHome(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $totalUsers = EndUser::where('role', '!=', 1)->count();
        $totalCategories = Categories::count();
        $totalProducts = Products::count();
        $totalBranches = Branches::count();

        return view('super_admin.super_admin_home', compact('currentDate', 'currentTime', 'totalUsers', 'totalCategories', 'totalProducts', 'totalBranches'));
    }

    public function superAdminProfile(): View
    {
        $loggedUser = EndUser::find(session('user_id'));

        return view('super_admin.super_admin_profile', compact('loggedUser'));
    }

    public function superAdminSearch(Request $request): View
    {
        $query = $request->get('q', '');
        $results = [
            'products' => [],
            'users' => [],
            'categories' => [],
            'branches' => [],
        ];

        if (! empty($query)) {
            // Search in products (including post_scriptum)
            $results['products'] = Products::with(['branch', 'category'])
                ->where('product_name', 'like', "%{$query}%")
                ->orWhere('post_scriptum', 'like', "%{$query}%")
                ->orWhere('single_price', 'like', "%{$query}%")
                ->get()
                ->map(function ($product) {
                    $product->created_at_formatted = $product->created_at->format('d/m/Y H:i');
                    $product->updated_at_formatted = $product->updated_at->format('d/m/Y H:i');

                    return $product;
                });

            // Search in users
            $results['users'] = EndUser::where('username', 'like', "%{$query}%")
                ->where('role', '!=', 1) // Exclude super admin
                ->get()
                ->map(function ($user) {
                    $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
                    $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');

                    return $user;
                });

            // Search in categories
            $results['categories'] = Categories::where('category_name', 'like', "%{$query}%")
                ->get()
                ->map(function ($category) {
                    $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                    $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                    return $category;
                });

            // Search in branches
            $results['branches'] = Branches::where('branche_name', 'like', "%{$query}%")
                ->get()
                ->map(function ($branch) {
                    $branch->created_at_formatted = $branch->created_at->format('d/m/Y H:i');
                    $branch->updated_at_formatted = $branch->updated_at->format('d/m/Y H:i');

                    return $branch;
                });
        }

        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];

        return view('super_admin.super_admin_search', compact('currentDate', 'currentTime', 'results', 'query'));
    }

    public function updateUsername(Request $request): RedirectResponse
    {
        $id = session('user_id');
        $request->validate([
            'username' => 'required|string|max:255|min:2|unique:end_user,username,' . $id,
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.unique' => "Ce nom d'utilisateur existe déjà.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",

        ]);

        try {
            $user = EndUser::findOrFail($id);
            $user->username = $request->username;
            $user->save();

            return back()->with('success', 'Nom d\'utilisateur modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
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
            $user = EndUser::findOrFail(session('user_id'));

            if (! Hash::check($request->current_password, $user->password)) {
                return back()->with('error', 'Le mot de passe actuel est incorrect.');
            }

            $user->password = Hash::make($request->new_password);
            $user->save();

            return back()->with('success', 'Mot de passe modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function updateThemeFromProfile(Request $request): RedirectResponse
    {
        $request->validate([
            'theme' => 'required|string|in:light,dark',
        ]);

        try {
            $user = EndUser::findOrFail(session('user_id'));
            $user->theme = $request->theme;
            $user->save();
            session()->put('theme', $request->theme);

            return back()->with('success', 'Thème modifié avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage());
        }
    }

    public function superAdminUsers(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        $superAdminExists = EndUser::where('role', 1)->exists();
        $users = EndUser::where('role', '!=', 1)
            ->where('id', '!=', $currentUserId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
                $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');
                $user->last_time_connect_formatted = $user->last_time_connect
                    ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $user->last_time_connect)->format('d/m/Y H:i')
                    : 'Jamais';

                return $user;
            });

        return view('super_admin.super_admin_users', compact(
            'currentDate',
            'currentTime',
            'users',
            'superAdminExists'
        ));
    }

    public function superAdminUsersActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        $superAdminExists = EndUser::where('role', 1)->exists();

        $users = EndUser::where('status', 1)
            ->where('role', '!=', 1)
            ->where('id', '!=', $currentUserId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
                $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');
                $user->last_time_connect_formatted = $user->last_time_connect
                    ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $user->last_time_connect)->format('d/m/Y H:i')
                    : 'Jamais';

                return $user;
            });

        return view('super_admin.super_admin_users_active', compact(
            'currentDate',
            'currentTime',
            'users',
            'superAdminExists'
        ));
    }

    public function superAdminUsersBlocked(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        $superAdminExists = EndUser::where('role', 1)->exists();

        $users = EndUser::where('status', 2)
            ->where('role', '!=', 1)
            ->where('id', '!=', $currentUserId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
                $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');
                $user->last_time_connect_formatted = $user->last_time_connect
                    ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $user->last_time_connect)->format('d/m/Y H:i')
                    : 'Jamais';

                return $user;
            });

        return view('super_admin.super_admin_users_block', compact(
            'currentDate',
            'currentTime',
            'users',
            'superAdminExists'
        ));
    }

    public function superAdminUsersDeleted(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        $superAdminExists = EndUser::where('role', 1)->exists();

        $users = EndUser::where('status', 0)
            ->where('role', '!=', 1)
            ->where('id', '!=', $currentUserId)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($user) {
                $user->created_at_formatted = $user->created_at->format('d/m/Y H:i');
                $user->updated_at_formatted = $user->updated_at->format('d/m/Y H:i');
                $user->last_time_connect_formatted = $user->last_time_connect
                    ? \Carbon\Carbon::createFromFormat('d/m/Y H:i', $user->last_time_connect)->format('d/m/Y H:i')
                    : 'Jamais';

                return $user;
            });

        return view('super_admin.super_admin_users_trash', compact(
            'currentDate',
            'currentTime',
            'users',
            'superAdminExists'
        ));
    }

    public function blockUser(Request $request): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);

            $user = EndUser::findOrFail($request->user_id);
            $user->status = 2;
            $user->updated_at = now();
            $user->save();

            return back()->with('success', 'Utilisateur bloqué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du blocage : ' . $e->getMessage());
        }
    }

    public function unblockUser(Request $request): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);

            $user = EndUser::findOrFail($request->user_id);
            $user->status = 1;
            $user->updated_at = now();
            $user->save();

            return back()->with('success', 'Utilisateur débloqué avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du déblocage : ' . $e->getMessage());
        }
    }

    public function deleteUser(Request $request): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);

            $user = EndUser::findOrFail($request->user_id);
            $user->status = 0;
            $user->updated_at = now();
            $user->save();

            return back()->with('success', 'Utilisateur supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function restoreUser(Request $request): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);

            $user = EndUser::findOrFail($request->user_id);
            $user->status = 1;
            $user->updated_at = now();
            $user->save();

            return back()->with('success', 'Utilisateur restauré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la restauration : ' . $e->getMessage());
        }
    }

    public function permanentDeleteUser(Request $request): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);

            $user = EndUser::findOrFail($request->user_id);
            $user->forceDelete();

            return back()->with('success', 'Utilisateur supprimé définitivement.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression définitive : ' . $e->getMessage());
        }
    }

    public function handleUserAction(Request $request, string $action): RedirectResponse
    {
        try {
            $request->validate(['user_id' => 'required|exists:end_user,id']);
            $user = EndUser::findOrFail($request->user_id);
            $q = $request->q ? '?q=' . $request->q : '';

            switch ($action) {
                case 'block':
                    $user->status = 2;
                    $message = 'Utilisateur bloqué avec succès.';
                    break;
                case 'unblock':
                    $user->status = 1;
                    $message = 'Utilisateur débloqué avec succès.';
                    break;
                case 'delete':
                    $user->status = 0;
                    $message = 'Utilisateur supprimé avec succès.';
                    break;
                case 'restore':
                    $user->status = 1;
                    $message = 'Utilisateur restauré avec succès.';
                    break;
                case 'erase':
                    $user->forceDelete();
                    $message = 'Utilisateur supprimé définitivement.';

                    return redirect()->to('/super_admin_search' . $q)->with('success', $message);
                default:
                    return back()->with('error', 'Action invalide.');
            }

            $user->updated_at = now();
            $user->save();

            return redirect()->to('/super_admin_search' . $q)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function createUsersAdmins(Request $request): RedirectResponse
    {
        // Vérifier si on essaie de créer un super admin
        if ($request->role == 1) {
            // Vérifier si un super admin existe déjà
            $existingSuperAdmin = EndUser::where('role', 1)->first();

            if ($existingSuperAdmin) {
                return back()->withErrors(['role' => 'Un super admin existe déjà. Vous ne pouvez pas en créer un second.'])
                    ->withInput();
            }
        }
        $request->validate([
            'username' => 'required|string|max:255|min:2|unique:end_user,username',
            'password' => 'required|string|max:255|min:4',
            'role' => 'required|integer|in:1,2,3',
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'username.unique' => "Ce nom d'utilisateur existe déjà.",
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.string' => 'Le mot de passe doit être une chaîne de caractères.',
            'password.max' => 'Le mot de passe doit contenir au maximum 255 caractères.',
            'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.integer' => 'Le rôle doit être un nombre entier.',
            'role.in' => 'Le rôle est incorrect.',
        ]);
        try {
            EndUser::create([
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'status' => 1,
                'last_time_connect' => null,
            ]);

            return redirect()->route('super_admin_users')
                ->with('success', 'Utilisateur créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function updateUser(Request $request): RedirectResponse
    {
        $id = $request->input('user_id');
        $request->validate([
            'username' => 'string|max:255|min:2|unique:end_user,username,' . $id,
            'role' => 'integer|in:2,3',
        ], [
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'username.unique' => "Ce nom d'utilisateur existe déjà.",
            'role.integer' => 'Le rôle doit être un nombre entier.',
            'role.in' => 'Le rôle doit être admin (2) ou utilisateur (3).',
        ]);
        try {
            $user = EndUser::findOrFail($id);
            $user->update([
                'username' => $request->username,
                'role' => $request->role,
            ]);

            return redirect()->route('super_admin_users')
                ->with('success', 'Utilisateur modifié avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function editUserFromSearch(Request $request, string $id): View|RedirectResponse
    {
        try {
            $user = EndUser::findOrFail($id);
            $q = $request->query('q', '');

            return view('super_admin.edit-user-search', [
                'user' => $user,
                'q' => $q,
            ]);
        } catch (\Exception $e) {
            return redirect()->to('/super_admin_search')
                ->with('error', 'Utilisateur non trouvé.' . $e->getMessage());
        }
    }

    public function updateUserFromSearch(Request $request): RedirectResponse
    {
        $id = $request->input('user_id');
        $q = $request->input('q', '');

        $request->validate([
            'username' => 'required|string|unique:end_user,username,' . $id . '|max:255|min:2',
            'role' => 'required|integer|in:2,3',
        ], [
            'username.required' => "Le nom d'utilisateur est obligatoire.",
            'username.string' => "Le nom d'utilisateur doit être une chaîne de caractères.",
            'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            'username.unique' => "Ce nom d'utilisateur \":input\" existe déjà.",

            'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
            'role.required' => 'Le rôle est obligatoire.',
            'role.integer' => 'Le rôle doit être un nombre.',
            'role.in' => 'Le rôle doit être admin (2) ou utilisateur (3).',
        ]);

        try {
            $user = EndUser::findOrFail($id);

            $user->update([
                'username' => $request->username,
                'role' => $request->role,
            ]);

            if (! empty($q)) {
                return redirect()->to('/super_admin_search?q=' . $q)
                    ->with('success', 'Utilisateur modifié avec succès !');
            }

            return redirect()->to('/super_admin_search')
                ->with('success', 'Utilisateur modifié avec succès !');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la modification : ' . $e->getMessage())
                ->withInput();
        }
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

                return response()->json(['success' => true]);
            }

            return response()->json(['success' => false, 'message' => 'Utilisateur non trouvé'], 404);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}
