<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EndUser;

class UsersController extends Controller
{
    //
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
