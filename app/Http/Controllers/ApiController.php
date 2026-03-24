<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);
        $user = User::where('username', $credentials['username'])->first();

        if ($user && password_verify($credentials['password'], $user->password)) {
            if ($user->status !== 1) {
                return response()->json(['error' => 'Compte bloqué ou supprimé'], 401);
            }

            $token = bin2hex(random_bytes(40));

            return response()->json([
                'success' => true,
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
                'token' => $token,
            ]);
        }

        return response()->json(['error' => 'Identifiants invalides'], 401);
    }

    public function logout(Request $request): JsonResponse
    {
        return response()->json(['success' => true]);
    }

    public function products(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);

        $query = Products::with(['branch', 'category']);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $products = $query->orderBy('updated_at', 'desc')->paginate(10);

        return response()->json($products);
    }

    public function productsCounts(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $counts = [
            'total' => Products::count(),
            'active' => Products::where('status', 1)->count(),
            'blocked' => Products::where('status', 2)->count(),
            'deleted' => Products::where('status', 0)->count(),
            'role' => $user->role,
        ];

        return response()->json($counts);
    }

    public function blockProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $product = Products::find($validated['id']);

        if (! $product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $product->status = 2;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Produit bloqué avec succès']);
    }

    public function unblockProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $product = Products::find($validated['id']);

        if (! $product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $product->status = 1;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Produit débloqué avec succès']);
    }

    public function deleteProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $product = Products::find($validated['id']);

        if (! $product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $product->status = 0;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Produit supprimé avec succès']);
    }

    public function restoreProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $product = Products::find($validated['id']);

        if (! $product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $product->status = 1;
        $product->save();

        return response()->json(['success' => true, 'message' => 'Produit restauré avec succès']);
    }

    public function eraseProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $validated = $request->validate([
            'id' => 'required|integer',
        ]);

        $product = Products::find($validated['id']);

        if (! $product) {
            return response()->json(['error' => 'Produit non trouvé'], 404);
        }

        $product->delete();

        return response()->json(['success' => true, 'message' => 'Produit supprimé définitivement']);
    }

    public function createProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'product_name' => 'required|string|max:255|min:2|unique:products,product_name',
                'single_price' => 'required|numeric|min:0',
                'detailed_price' => 'nullable|string|max:255',
                'post_scriptum' => 'nullable|string|max:1000',
                'branch_id' => 'required|integer|exists:branches,id',
                'category_id' => 'required|integer|exists:categories,id',
            ], [
                'product_name.required' => 'Le nom du produit est obligatoire.',
                'product_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
                'product_name.max' => 'Le nom du produit doit contenir au maximum 255 caractères.',
                'product_name.min' => 'Le nom du produit doit contenir au moins 2 caractères.',
                'product_name.unique' => 'Ce produit existe déjà.',
                'single_price.required' => 'Le prix unitaire est obligatoire.',
                'single_price.numeric' => 'Le prix doit être un nombre.',
                'single_price.min' => 'Le prix ne peut pas être négatif.',
                'branch_id.required' => 'Veuillez sélectionner une branche.',
                'branch_id.exists' => 'La branche sélectionnée n\'existe pas.',
                'category_id.required' => 'Veuillez sélectionner une catégorie.',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            ]);

            $product = Products::create([
                'product_name' => $validated['product_name'],
                'single_price' => $validated['single_price'],
                'detailed_price' => $validated['detailed_price'] ?? null,
                'post_scriptum' => $validated['post_scriptum'] ?? null,
                'branch_id' => $validated['branch_id'],
                'category_id' => $validated['category_id'],
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Produit créé avec succès',
                'product' => $product,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'error' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la création : '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateProduct(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $id = $request->input('prod_id');

        try {
            $validated = $request->validate([
                'product_name' => 'string|max:255|min:2|unique:products,product_name,'.$id,
                'single_price' => 'numeric|min:0',
                'detailed_price' => 'nullable|string|max:255',
                'post_scriptum' => 'nullable|string|max:1000',
                'branch_id' => 'exists:branches,id',
                'category_id' => 'exists:categories,id',
            ], [
                'product_name.string' => 'Le nom du produit doit être une chaîne de caractères.',
                'product_name.max' => 'Le nom du produit doit contenir au maximum 255 caractères.',
                'product_name.min' => 'Le nom du produit doit contenir au moins 2 caractères.',
                'product_name.unique' => 'Ce produit existe déjà.',
                'single_price.numeric' => 'Le prix doit être un nombre.',
                'single_price.min' => 'Le prix ne peut pas être négatif.',
                'branch_id.exists' => 'La branche sélectionnée n\'existe pas.',
                'category_id.exists' => 'La catégorie sélectionnée n\'existe pas.',
            ]);

            $product = Products::findOrFail($id);
            $product->update([
                'product_name' => $request->product_name,
                'single_price' => $request->single_price,
                'detailed_price' => $request->detailed_price,
                'post_scriptum' => $request->post_scriptum,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
            ]);

            $product->load(['branch', 'category']);

            return response()->json([
                'success' => true,
                'message' => 'Produit modifié avec succès',
                'product' => $product,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'error' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    public function categories(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $categories = Categories::where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($categories);
    }

    public function branches(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $branches = Branches::where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($branches);
    }

    public function search(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $query = $request->query('q', '');

        if (strlen($query) < 2) {
            return response()->json([
                'products' => [],
                'users' => [],
                'categories' => [],
                'branches' => [],
            ]);
        }

        $user = User::find($userId);
        $userRole = $user ? $user->role : 3;

        // Search in products (including post_scriptum and single_price)
        $products = Products::with(['branch', 'category'])
            ->where(function ($q) use ($query) {
                $q->where('product_name', 'like', '%'.$query.'%')
                    ->orWhere('post_scriptum', 'like', '%'.$query.'%')
                    ->orWhere('single_price', 'like', '%'.$query.'%');
            })
            ->when($userRole >= 2, function ($q) {
                return $q->where('status', 1);
            })
            ->limit(10)
            ->get()
            ->map(function ($product) {
                $product->created_at_formatted = \Carbon\Carbon::parse($product->created_at)->format('d/m/Y H:i');
                $product->updated_at_formatted = \Carbon\Carbon::parse($product->updated_at)->format('d/m/Y H:i');

                return $product;
            });

        // Search in users (only Super Admin can see users, and exclude super admins)
        $users = [];
        if ($userRole === 1) {
            $users = User::where('username', 'like', '%'.$query.'%')
                ->where('role', '!=', 1)
                ->limit(10)
                ->get()
                ->map(function ($user) {
                    $user->created_at_formatted = \Carbon\Carbon::parse($user->created_at)->format('d/m/Y H:i');
                    $user->updated_at_formatted = \Carbon\Carbon::parse($user->updated_at)->format('d/m/Y H:i');

                    return $user;
                });
        }

        // Search in categories
        $categories = Categories::where('category_name', 'like', '%'.$query.'%')
            ->where('status', 1)
            ->limit(10)
            ->get()
            ->map(function ($category) {
                $category->created_at_formatted = \Carbon\Carbon::parse($category->created_at)->format('d/m/Y H:i');
                $category->updated_at_formatted = \Carbon\Carbon::parse($category->updated_at)->format('d/m/Y H:i');

                return $category;
            });

        // Search in branches
        $branches = Branches::where('branche_name', 'like', '%'.$query.'%')
            ->where('status', 1)
            ->limit(10)
            ->get()
            ->map(function ($branch) {
                $branch->created_at_formatted = \Carbon\Carbon::parse($branch->created_at)->format('d/m/Y H:i');
                $branch->updated_at_formatted = \Carbon\Carbon::parse($branch->updated_at)->format('d/m/Y H:i');

                return $branch;
            });

        return response()->json([
            'products' => $products,
            'users' => $users,
            'categories' => $categories,
            'branches' => $branches,
        ]);
    }

    // GET /api/dashboard/stats - Stats based on user role
    public function dashboardStats(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $stats = [
            'role' => $user->role,
            'role_name' => match ($user->role) {
                1 => 'Super Administrateur',
                2 => 'Administrateur',
                3 => 'Lecteur',
                default => 'Inconnu'
            },
        ];

        // Super Admin: sees all stats including users
        if ($user->role == 1) {
            $stats['users'] = [
                'total' => User::where('role', '!=', 1)->count(),
                'active' => User::where('role', '!=', 1)->where('status', 1)->count(),
                'blocked' => User::where('role', '!=', 1)->where('status', 2)->count(),
            ];
        }

        // All roles (Super Admin, Admin, Reader) see these stats
        $stats['categories'] = [
            'total' => Categories::count(),
            'active' => Categories::where('status', 1)->count(),
            'blocked' => Categories::where('status', 2)->count(),
        ];

        $stats['products'] = [
            'total' => Products::count(),
            'active' => Products::where('status', 1)->count(),
            'blocked' => Products::where('status', 2)->count(),
        ];

        $stats['branches'] = [
            'total' => Branches::count(),
            'active' => Branches::where('status', 1)->count(),
            'blocked' => Branches::where('status', 2)->count(),
        ];

        return response()->json($stats);
    }

    public function user(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }
        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role,
        ]);
    }

    public function updateUsername(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|min:2|unique:users,username,'.$userId,
            ], [
                'username.required' => "Le nom d'utilisateur est obligatoire.",
                'username.unique' => "Ce nom d'utilisateur existe déjà.",
                'username.min' => "Le nom d'utilisateur doit contenir au moins 2 caractères.",
                'username.max' => "Le nom d'utilisateur doit contenir au maximum 255 caractères.",
            ]);

            $user = User::findOrFail($userId);
            $user->username = $validated['username'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => "Nom d'utilisateur modifié avec succès.",
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'error' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateEmail(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        try {
            $validated = $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email,'.$userId.'|ends_with:gmail.com',
            ], [
                'email.required' => "L'adresse email est obligatoire.",
                'email.email' => "L'adresse email doit être valide.",
                'email.max' => "L'adresse email doit contenir au maximum 255 caractères.",
                'email.unique' => 'Cette adresse email existe déjà.',
                'email.ends_with' => "L'adresse email doit être une adresse Gmail (@gmail.com).",
            ]);

            $user = User::findOrFail($userId);
            $user->email = $validated['email'];
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Adresse email modifiée avec succès.',
                'user' => [
                    'id' => $user->id,
                    'username' => $user->username,
                    'email' => $user->email,
                    'role' => $user->role,
                ],
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'error' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    public function updatePassword(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        try {
            $validated = $request->validate([
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

            $user = User::findOrFail($userId);

            if (! Hash::check($validated['current_password'], $user->password)) {
                return response()->json([
                    'error' => 'Le mot de passe actuel est incorrect.',
                ], 422);
            }

            $user->password = Hash::make($validated['new_password']);
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Mot de passe modifié avec succès.',
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            $errors = $e->errors();
            $firstError = collect($errors)->flatten()->first();

            return response()->json([
                'error' => $firstError,
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }
}
