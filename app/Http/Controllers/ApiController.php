<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

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

    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email|ends_with:gmail.com|exists:users,email',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'L\'adresse email doit être valide.',
            'email.ends_with' => 'L\'adresse email doit être une adresse Gmail (@gmail.com).',
            'email.exists' => 'Aucun compte trouvé avec cette adresse email.',
        ]);

        try {
            $status = Password::sendResetLink(
                $request->only('email')
            );

            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Un lien de réinitialisation a été envoyé à votre adresse email.',
                ]);
            }

            return response()->json([
                'error' => 'Impossible d\'envoyer le lien de réinitialisation. Status: '.$status,
            ], 500);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Password reset error: '.$e->getMessage());

            return response()->json([
                'error' => 'Erreur: '.$e->getMessage(),
            ], 500);
        }
    }

    public function resetPassword(Request $request): JsonResponse
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
            return response()->json([
                'success' => true,
                'message' => 'Mot de passe réinitialisé avec succès. Veuillez vous connecter.',
            ]);
        }

        return response()->json([
            'error' => 'Impossible de réinitialiser le mot de passe.',
        ], 500);
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
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $query = Categories::query();

        // Role-based filtering
        if ($user->role === 3) {
            // Reader: only active categories
            $query->where('status', 1);
        } elseif ($user->role === 2) {
            // Admin: active (1) and blocked (2), no deleted
            if ($status === 'all') {
                $query->whereIn('status', [1, 2]);
            } elseif (in_array($status, [1, 2])) {
                $query->where('status', $status);
            } else {
                $query->where('status', 1);
            }
        } else {
            // Super Admin: all statuses
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $categories = $query->orderBy('updated_at', 'desc')->paginate(20);

        return response()->json($categories);
    }

    public function categoriesCounts(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $counts = [];

        if ($user->role === 1) {
            // Super Admin: all statuses
            $counts = [
                'total' => Categories::count(),
                'active' => Categories::where('status', 1)->count(),
                'blocked' => Categories::where('status', 2)->count(),
                'deleted' => Categories::where('status', 0)->count(),
            ];
        } elseif ($user->role === 2) {
            // Admin: active and blocked
            $counts = [
                'total' => Categories::whereIn('status', [1, 2])->count(),
                'active' => Categories::where('status', 1)->count(),
                'blocked' => Categories::where('status', 2)->count(),
            ];
        } else {
            // Reader: active only
            $counts = [
                'active' => Categories::where('status', 1)->count(),
            ];
        }

        $counts['role'] = $user->role;

        return response()->json($counts);
    }

    public function createCategory(Request $request): JsonResponse
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
                'category_name' => 'required|string|max:255|min:2|unique:categories,category_name',
            ], [
                'category_name.required' => 'Le nom de la catégorie est obligatoire.',
                'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
                'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
                'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
                'category_name.unique' => 'Cette catégorie existe déjà.',
            ]);

            $category = Categories::create([
                'category_name' => $validated['category_name'],
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie créée avec succès',
                'category' => $category,
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

    public function updateCategory(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $id = $request->input('category_id');

        try {
            $validated = $request->validate([
                'category_name' => 'required|string|max:255|min:2|unique:categories,category_name,'.$id,
            ], [
                'category_name.required' => 'Le nom de la catégorie est obligatoire.',
                'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
                'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
                'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
                'category_name.unique' => 'Cette catégorie existe déjà.',
            ]);

            $category = Categories::findOrFail($id);
            $category->update([
                'category_name' => $validated['category_name'],
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Catégorie modifiée avec succès',
                'category' => $category,
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

    public function blockCategory(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:categories,id',
            ]);

            $category = Categories::findOrFail($validated['id']);
            $category->status = 2;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie bloquée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du blocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function unblockCategory(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:categories,id',
            ]);

            $category = Categories::findOrFail($validated['id']);
            $category->status = 1;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie débloquée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du déblocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteCategory(Request $request): JsonResponse
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
                'id' => 'required|integer|exists:categories,id',
            ]);

            $category = Categories::findOrFail($validated['id']);

            if ($user->role === 1) {
                // Super Admin: soft delete (status = 0)
                $category->status = 0;
                $category->save();
                $message = 'Catégorie supprimée avec succès';
            } else {
                // Admin: status = 2 (blocked)
                $category->status = 2;
                $category->save();
                $message = 'Catégorie supprimée avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }

    public function restoreCategory(Request $request): JsonResponse
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
                'id' => 'required|integer|exists:categories,id',
            ]);

            $category = Categories::findOrFail($validated['id']);
            $category->status = 1;
            $category->save();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie restaurée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la restauration : '.$e->getMessage(),
            ], 500);
        }
    }

    public function eraseCategory(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:categories,id',
            ]);

            $category = Categories::findOrFail($validated['id']);
            $category->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Catégorie supprimée définitivement',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression définitive : '.$e->getMessage(),
            ], 500);
        }
    }

    public function branches(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $query = Branches::query();

        // Role-based filtering
        if ($user->role === 3) {
            // Reader: only active branches
            $query->where('status', 1);
        } elseif ($user->role === 2) {
            // Admin: active (1) and blocked (2)
            if ($status !== 'all') {
                $query->whereIn('status', [1, 2]);
                if ($status == 1 || $status == 2) {
                    $query->where('status', $status);
                }
            }
        } else {
            // Super Admin: all statuses
            if ($status !== 'all') {
                $query->where('status', $status);
            }
        }

        $branches = $query->orderBy('updated_at', 'desc')->paginate(20);

        return response()->json($branches);
    }

    public function branchesCounts(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }

        $counts = [];

        if ($user->role === 1) {
            // Super Admin: all statuses
            $counts = [
                'total' => Branches::count(),
                'active' => Branches::where('status', 1)->count(),
                'blocked' => Branches::where('status', 2)->count(),
                'deleted' => Branches::where('status', 0)->count(),
            ];
        } elseif ($user->role === 2) {
            // Admin: active and blocked
            $counts = [
                'total' => Branches::whereIn('status', [1, 2])->count(),
                'active' => Branches::where('status', 1)->count(),
                'blocked' => Branches::where('status', 2)->count(),
            ];
        } else {
            // Reader: active only
            $counts = [
                'active' => Branches::where('status', 1)->count(),
            ];
        }

        $counts['role'] = $user->role;

        return response()->json($counts);
    }

    public function createBranch(Request $request): JsonResponse
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
                'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name',
                'location' => 'nullable|string|max:255',
            ], [
                'branche_name.required' => 'Le nom de la branche est obligatoire.',
                'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
                'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
                'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
                'branche_name.unique' => 'Cette branche existe déjà.',
            ]);

            $branch = Branches::create([
                'branche_name' => $validated['branche_name'],
                'location' => $validated['location'] ?? null,
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branche créée avec succès',
                'branch' => $branch,
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

    public function updateBranch(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || ($user->role !== 1 && $user->role !== 2)) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        $id = $request->input('branch_id');

        try {
            $validated = $request->validate([
                'branche_name' => 'required|string|max:255|min:2|unique:branches,branche_name,'.$id,
                'location' => 'nullable|string|max:255',
            ], [
                'branche_name.required' => 'Le nom de la branche est obligatoire.',
                'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
                'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
                'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
                'branche_name.unique' => 'Cette branche existe déjà.',
            ]);

            $branch = Branches::findOrFail($id);
            $branch->update([
                'branche_name' => $validated['branche_name'],
                'location' => $validated['location'] ?? $branch->location,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Branche modifiée avec succès',
                'branch' => $branch,
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

    public function blockBranch(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:branches,id',
            ]);

            $branch = Branches::findOrFail($validated['id']);
            $branch->status = 2;
            $branch->save();

            return response()->json([
                'success' => true,
                'message' => 'Branche bloquée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du blocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function unblockBranch(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:branches,id',
            ]);

            $branch = Branches::findOrFail($validated['id']);
            $branch->status = 1;
            $branch->save();

            return response()->json([
                'success' => true,
                'message' => 'Branche débloquée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du déblocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteBranch(Request $request): JsonResponse
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
                'id' => 'required|integer|exists:branches,id',
            ]);

            $branch = Branches::findOrFail($validated['id']);

            if ($user->role === 1) {
                // Super Admin: soft delete (status = 0)
                $branch->status = 0;
                $branch->save();
                $message = 'Branche supprimée avec succès';
            } else {
                // Admin: status = 2 (blocked)
                $branch->status = 2;
                $branch->save();
                $message = 'Branche supprimée avec succès';
            }

            return response()->json([
                'success' => true,
                'message' => $message,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }

    public function restoreBranch(Request $request): JsonResponse
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
                'id' => 'required|integer|exists:branches,id',
            ]);

            $branch = Branches::findOrFail($validated['id']);
            $branch->status = 1;
            $branch->save();

            return response()->json([
                'success' => true,
                'message' => 'Branche restaurée avec succès',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la restauration : '.$e->getMessage(),
            ], 500);
        }
    }

    public function eraseBranch(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Non autorisé'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|integer|exists:branches,id',
            ]);

            $branch = Branches::findOrFail($validated['id']);
            $branch->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Branche supprimée définitivement',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression définitive : '.$e->getMessage(),
            ], 500);
        }
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
        $categoriesQuery = Categories::where('category_name', 'like', '%'.$query.'%');

        // Role-based filtering for categories search
        if ($userRole === 3) {
            // Reader: only active
            $categoriesQuery->where('status', 1);
        } elseif ($userRole === 2) {
            // Admin: active and blocked
            $categoriesQuery->whereIn('status', [1, 2]);
        }
        // Super Admin: all statuses

        $categories = $categoriesQuery
            ->limit(10)
            ->get()
            ->map(function ($category) {
                $category->created_at_formatted = \Carbon\Carbon::parse($category->created_at)->format('d/m/Y H:i');
                $category->updated_at_formatted = \Carbon\Carbon::parse($category->updated_at)->format('d/m/Y H:i');

                return $category;
            });

        // Search in branches
        $branchesQuery = Branches::where('branche_name', 'like', '%'.$query.'%');

        // Role-based filtering for branches search
        if ($userRole === 3) {
            // Reader: only active
            $branchesQuery->where('status', 1);
        } elseif ($userRole === 2) {
            // Admin: active and blocked
            $branchesQuery->whereIn('status', [1, 2]);
        }
        // Super Admin: all statuses

        $branches = $branchesQuery
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
            'theme' => $user->theme,
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
            return response()->json([
                'error' => $e->errors(),
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
            return response()->json([
                'error' => $e->errors(),
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
            return response()->json([
                'error' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    public function updateTheme(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $request->validate([
            'theme' => 'required|string|in:light,dark',
        ]);

        try {
            $user = User::findOrFail($userId);
            $user->theme = $request->theme;
            $user->save();

            return response()->json([
                'success' => true,
                'message' => 'Thème modifié avec succès.',
                'theme' => $user->theme,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la modification : '.$e->getMessage(),
            ], 500);
        }
    }

    public function users(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        $status = $request->query('status', 'all');
        $search = $request->query('search', '');

        $query = User::where('id', '!=', $userId);

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('username', 'like', '%'.$search.'%')
                    ->orWhere('email', 'like', '%'.$search.'%');
            });
        }

        $users = $query->orderBy('updated_at', 'desc')->paginate(10);

        return response()->json($users);
    }

    public function usersCounts(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        $counts = [
            'total' => User::where('id', '!=', $userId)->count(),
            'active' => User::where('status', 1)->where('id', '!=', $userId)->count(),
            'blocked' => User::where('status', 2)->where('id', '!=', $userId)->count(),
            'deleted' => User::where('status', 0)->where('id', '!=', $userId)->count(),
        ];

        return response()->json($counts);
    }

    public function createUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'username' => 'required|string|max:255|min:2|unique:users,username',
                'email' => 'required|string|email|max:255|unique:users,email|ends_with:gmail.com',
                'password' => 'required|string|min:4|max:255',
                'role' => 'required|integer|in:2,3',
            ], [
                'username.required' => "Le nom d'utilisateur est obligatoire.",
                'username.unique' => "Ce nom d'utilisateur existe déjà.",
                'email.required' => "L'adresse email est obligatoire.",
                'email.email' => "L'adresse email doit être valide.",
                'email.unique' => 'Cette adresse email existe déjà.',
                'email.ends_with' => "L'adresse email doit être une adresse Gmail.",
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 4 caractères.',
                'role.required' => 'Le rôle est obligatoire.',
                'role.in' => 'Le rôle doit être admin (2) ou utilisateur (3).',
            ]);

            $newUser = User::create([
                'username' => $validated['username'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'status' => 1,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => $newUser,
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

    public function updateUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'username' => 'sometimes|string|max:255|min:2|unique:users,username,'.$request->user_id,
                'email' => 'sometimes|string|email|max:255|unique:users,email,'.$request->user_id.'|ends_with:gmail.com',
                'role' => 'sometimes|integer|in:2,3',
            ], [
                'username.unique' => "Ce nom d'utilisateur existe déjà.",
                'email.unique' => 'Cette adresse email existe déjà.',
                'email.ends_with' => "L'adresse email doit être une adresse Gmail.",
                'role.in' => 'Le rôle doit être admin (2) ou utilisateur (3).',
            ]);

            $targetUser = User::findOrFail($request->user_id);

            if ($request->has('username')) {
                $targetUser->username = $validated['username'];
            }
            if ($request->has('email')) {
                $targetUser->email = $validated['email'];
            }
            if ($request->has('role')) {
                $targetUser->role = $validated['role'];
            }

            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur modifié avec succès.',
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

    public function blockUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['id']);
            $targetUser->status = 2;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur bloqué avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du blocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function unblockUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['id']);
            $targetUser->status = 1;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur débloqué avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors du déblocage : '.$e->getMessage(),
            ], 500);
        }
    }

    public function deleteUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['id']);
            $targetUser->status = 0;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression : '.$e->getMessage(),
            ], 500);
        }
    }

    public function restoreUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['id']);
            $targetUser->status = 1;
            $targetUser->save();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur restauré avec succès.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la restauration : '.$e->getMessage(),
            ], 500);
        }
    }

    public function eraseUser(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (! $userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }

        $user = User::find($userId);

        if (! $user || $user->role !== 1) {
            return response()->json(['error' => 'Accès restreint aux super administrateurs'], 403);
        }

        try {
            $validated = $request->validate([
                'id' => 'required|exists:users,id',
            ]);

            $targetUser = User::findOrFail($validated['id']);
            $targetUser->forceDelete();

            return response()->json([
                'success' => true,
                'message' => 'Utilisateur supprimé définitivement.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la suppression définitive : '.$e->getMessage(),
            ], 500);
        }
    }
}
