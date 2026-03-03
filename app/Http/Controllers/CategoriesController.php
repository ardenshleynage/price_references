<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Models\Categories;
/* use Carbon\Carbon; */
use App\Models\EndUser;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CategoriesController extends Controller
{
    use DateTimeHelper;

    public function superAdminCategories(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();
        // Récupérer toutes les catégories
        $categories = Categories::orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($category) {
                // Formater les dates
                $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                return $category;
            });

        return view('super_admin.super_admin_categories', compact(
            'currentDate',
            'currentTime',
            'categories',
            'superAdminExists'
        ));
    }

    public function superAdminCategoriesActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        // Récupérer UNIQUEMENT les catégories actives (status = 1)
        $categories = Categories::where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($category) {
                $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                return $category;
            });

        return view('super_admin.super_admin_categories_active', compact(
            'currentDate',
            'currentTime',
            'categories',
            'superAdminExists'
        ));
    }

    public function superAdminCategoriesBlocked(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        // Récupérer UNIQUEMENT les catégories actives (status = 1)
        $categories = Categories::where('status', 2)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($category) {
                $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                return $category;
            });

        return view('super_admin.super_admin_categories_block', compact(
            'currentDate',
            'currentTime',
            'categories',
            'superAdminExists'
        ));
    }

    public function superAdminCategoriesDeleted(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        // Récupérer UNIQUEMENT les catégories actives (status = 1)
        $categories = Categories::where('status', 0)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($category) {
                $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                return $category;
            });

        return view('super_admin.super_admin_categories_trash', compact(
            'currentDate',
            'currentTime',
            'categories',
            'superAdminExists'
        ));
    }

    public function blockCategory(Request $request): RedirectResponse
    {
        try {
            $request->validate(['category_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->category_id);
            $category->status = 2;  // Bloqué
            $category->updated_at = now();
            $category->save();

            return back()->with('success', 'Catégorie bloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du blocage : ' . $e->getMessage());
        }
    }

    public function unblockCategory(Request $request): RedirectResponse
    {
        try {
            $request->validate(['category_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->category_id);
            $category->status = 1;  // Débloqué
            $category->updated_at = now();
            $category->save();

            return back()->with('success', 'Catégorie débloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du déblocage : ' . $e->getMessage());
        }
    }

    public function deleteCategory(Request $request): RedirectResponse
    {
        try {
            $request->validate(['category_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->category_id);
            $category->status = 0;  // Supprimé
            $category->updated_at = now();
            $category->save();

            return back()->with('success', 'Catégorie supprimée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function restoreCategory(Request $request): RedirectResponse
    {
        try {
            $request->validate(['category_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->category_id);
            $category->status = 1;  // Restauré
            $category->updated_at = now();
            $category->save();

            return back()->with('success', 'Catégorie restaurée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la restauration  : ' . $e->getMessage());
        }
    }

    public function permaneneDeleteCategory(Request $request): RedirectResponse
    {
        try {
            $request->validate(['category_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->category_id);
            $category->forceDelete();

            return back()->with('success', 'Catégorie supprimée définitivement.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression définitive  : ' . $e->getMessage());
        }
    }

    public function handleCategoryAction(Request $request, string $action): RedirectResponse
    {
        try {
            $request->validate(['categorie_id' => 'required|exists:categories,id']);
            $category = Categories::findOrFail($request->categorie_id);
            $q = $request->q ? '?q=' . $request->q : '';

            switch ($action) {
                case 'block':
                    $category->status = 2;
                    $message = 'Catégorie bloquée avec succès.';
                    break;
                case 'unblock':
                    $category->status = 1;
                    $message = 'Catégorie débloquée avec succès.';
                    break;
                case 'delete':
                    $category->status = 0;
                    $message = 'Catégorie supprimée avec succès.';
                    break;
                case 'restore':
                    $category->status = 1;
                    $message = 'Catégorie restaurée avec succès.';
                    break;
                case 'erase':
                    $category->forceDelete();
                    $message = 'Catégorie supprimée définitivement.';

                    return redirect()->to('/super_admin_search' . $q)->with('success', $message);
                default:
                    return back()->with('error', 'Action invalide.');
            }

            $category->updated_at = now();
            $category->save();

            return redirect()->to('/super_admin_search' . $q)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : ' . $e->getMessage());
        }
    }

    public function createCategories(Request $request): RedirectResponse
    {
        $request->validate([
            'category_name' => 'required|string|max:255|min:2|unique:categories,category_name',
        ], [
            'category_name.required' => 'Le nom de la catégorie est obligatoire.',
            'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
            'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
            'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
            'category_name.unique' => 'Cette catégorie existe déjà.',
        ]);
        try {
            // Création de l'utilisateur avec mot de passe hashé
            Categories::create([
                'category_name' => $request->category_name,
                'status' => 1,
            ]);

            return redirect()->route('super_admin_categories')
                ->with('success', 'Catégorie créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function updateCategories(Request $request): RedirectResponse
    {
        // Récupérer l'ID depuis le formulaire (champ caché)
        $id = $request->input('category_id');

        // Validation - inclure l'ID dans la règle unique pour exclure l'enregistrement actuel
        $request->validate([
            'category_name' => 'required|string|max:255|min:2|unique:categories,category_name,' . $id,
        ], [
            'category_name.required' => 'Le nom de la catégorie est obligatoire.',
            'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
            'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
            'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
            'category_name.unique' => 'Cette catégorie existe déjà.',
        ]);

        try {
            // Récupérer la branche avec l'ID du formulaire
            $category = Categories::findOrFail($id);

            // Mise à jour
            $category->update([
                'category_name' => $request->category_name,
            ]);

            // Si redirection depuis la recherche
            if ($request->has('q') && ! empty($request->q)) {
                return redirect()->to('/super_admin_search?q=' . $request->q)
                    ->with('success', 'Catégorie modifiée avec succès !');
            }

            return redirect()->route('super_admin_categories')
                ->with('success', 'Catégorie modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()])
                ->withInput();
        }
    }

    public function editFromSearch(Request $request, int $id): View|RedirectResponse
    {
        try {
            $category = Categories::findOrFail($id);
            $q = $request->get('q', '');

            return view('super_admin.edit-category-search', compact('category', 'q'));
        } catch (\Exception $e) {
            return redirect()->route('super_admin_categories')
                ->with('error', 'Catégorie introuvable.' . $e->getMessage());
        }
    }

    public function updateFromSearch(Request $request): RedirectResponse
    {
        $id = $request->input('category_id');
        $q = $request->input('q', '');

        $request->validate([
            'category_name' => 'required|string|max:255|min:2|unique:categories,category_name,' . $id,
        ], [
            'category_name.required' => 'Le nom de la catégorie est obligatoire.',
            'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
            'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
            'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
            'category_name.unique' => 'Cette catégorie existe déjà.',
        ]);

        try {
            $category = Categories::findOrFail($id);
            $category->update(['category_name' => $request->category_name]);

            if (! empty($q)) {
                return redirect()->to('/super_admin_search?q=' . $q)
                    ->with('success', 'Catégorie modifiée avec succès !');
            }

            return redirect()->route('super_admin_categories')
                ->with('success', 'Catégorie modifiée avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : ' . $e->getMessage()])
                ->withInput();
        }
    }
}
