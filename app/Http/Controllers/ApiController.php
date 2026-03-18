<?php

namespace App\Http\Controllers;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function login(Request $request): JsonResponse
    {
        $credentials = $request->validate([
            'username' => 'required',
            'password' => 'required'
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
                    'role' => $user->role
                ],
                'token' => $token
            ]);
        }
        return response()->json(['error' => 'Identifiants invalides'], 401);
    }
    // POST /api/logout
    public function logout(Request $request): JsonResponse
    {
        return response()->json(['success' => true]);
    }
    // GET /api/products
    public function products(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $products = Products::where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($products);
    }
    // GET /api/categories
    public function categories(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $categories = Categories::where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($categories);
    }
    // GET /api/branches
    public function branches(Request $request): JsonResponse
    {
        $status = $request->query('status', 1);
        $branches = Branches::where('status', $status)
            ->orderBy('id', 'desc')
            ->paginate(20);

        return response()->json($branches);
    }
    // GET /api/user
    public function user(Request $request): JsonResponse
    {
        $userId = $request->header('X-User-ID');

        if (!$userId) {
            return response()->json(['error' => 'Non autorisé'], 401);
        }
        $user = User::find($userId);

        if (!$user) {
            return response()->json(['error' => 'Utilisateur non trouvé'], 404);
        }
        return response()->json([
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'role' => $user->role
        ]);
    }
}
