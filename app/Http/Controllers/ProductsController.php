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

class ProductsController extends Controller
{
    use DateTimeHelper;

    public function superAdminProducts(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();
        // Récupérer toutes les catégories

        $products = Products::with(['branch', 'category'])
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($prod) {
                $prod->created_at_formatted = $prod->created_at->format('d/m/Y H:i');
                $prod->updated_at_formatted = $prod->updated_at->format('d/m/Y H:i');
                $prod->branch_name = $prod->branch ? $prod->branch->branche_name : 'Aucun';
                $prod->category_name = $prod->category ? $prod->category->category_name : 'Aucun';

                return $prod;
            });

        $branches = Branches::where('status', 1)->orderBy('branche_name')->get();
        $categories = Categories::where('status', 1)->orderBy('category_name')->get();

        return view('super_admin.super_admin_products', compact(
            'currentDate',
            'currentTime',
            'products',
            'superAdminExists',
            'branches',
            'categories'
        ));
    }

    public function superAdminProductsActive(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        $products = Products::with(['branch', 'category'])
            ->where('status', 1)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($prod) {
                $prod->created_at_formatted = $prod->created_at->format('d/m/Y H:i');
                $prod->updated_at_formatted = $prod->updated_at->format('d/m/Y H:i');
                $prod->branch_name = $prod->branch ? $prod->branch->branche_name : 'Aucun';
                $prod->category_name = $prod->category ? $prod->category->category_name : 'Aucun';

                return $prod;
            });

        $branches = Branches::where('status', 1)->orderBy('branche_name')->get();
        $categories = Categories::where('status', 1)->orderBy('category_name')->get();

        return view('super_admin.super_admin_products_active', compact(
            'currentDate',
            'currentTime',
            'products',
            'superAdminExists',
            'branches',
            'categories'

        ));
    }

    public function superAdminProductsBlocked(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        $products = Products::with(['branch', 'category'])
            ->where('status', 2)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($prod) {
                $prod->created_at_formatted = $prod->created_at->format('d/m/Y H:i');
                $prod->updated_at_formatted = $prod->updated_at->format('d/m/Y H:i');
                $prod->branch_name = $prod->branch ? $prod->branch->branche_name : 'Aucun';
                $prod->category_name = $prod->category ? $prod->category->category_name : 'Aucun';

                return $prod;
            });

        $branches = Branches::where('status', 1)->orderBy('branche_name')->get();
        $categories = Categories::where('status', 1)->orderBy('category_name')->get();

        return view('super_admin.super_admin_products_block', compact(
            'currentDate',
            'currentTime',
            'products',
            'superAdminExists',
            'branches',
            'categories'

        ));
    }

    public function superAdminProductsDeleted(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $superAdminExists = EndUser::where('role', 1)->exists();

        $products = Products::with(['branch', 'category'])
            ->where('status', 0)
            ->orderBy('updated_at', 'desc')
            ->get()
            ->map(function ($prod) {
                $prod->created_at_formatted = $prod->created_at->format('d/m/Y H:i');
                $prod->updated_at_formatted = $prod->updated_at->format('d/m/Y H:i');
                $prod->branch_name = $prod->branch ? $prod->branch->branche_name : 'Aucun';
                $prod->category_name = $prod->category ? $prod->category->category_name : 'Aucun';

                return $prod;
            });

        $branches = Branches::where('status', 1)->orderBy('branche_name')->get();
        $categories = Categories::where('status', 1)->orderBy('category_name')->get();

        return view('super_admin.super_admin_products_trash', compact(
            'currentDate',
            'currentTime',
            'products',
            'superAdminExists',
            'branches',
            'categories'

        ));
    }

    public function blockProducts(Request $request): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $prod = Products::findOrFail($request->prod_id);
            $prod->status = 2;  // Bloqué
            $prod->updated_at = now();
            $prod->save();

            return back()->with('success', 'Produit bloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du blocage : '.$e->getMessage());
        }
    }

    public function unblockProducts(Request $request): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $prod = Products::findOrFail($request->prod_id);
            $prod->status = 1;  // Bloqué
            $prod->updated_at = now();
            $prod->save();

            return back()->with('success', 'Produit débloquée avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors du déblocage : '.$e->getMessage());
        }
    }

    public function deleteProducts(Request $request): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $prod = Products::findOrFail($request->prod_id);
            $prod->status = 0;  // Bloqué
            $prod->updated_at = now();
            $prod->save();

            return back()->with('success', 'Produit supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression : '.$e->getMessage());
        }
    }

    public function restoreProducts(Request $request): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $prod = Products::findOrFail($request->prod_id);
            $prod->status = 1;  // Bloqué
            $prod->updated_at = now();
            $prod->save();

            return back()->with('success', 'Produit restauré avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la restauration : '.$e->getMessage());
        }
    }

    public function permaneneDeleteProducts(Request $request): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $prod = Products::findOrFail($request->prod_id);
            $prod->forceDelete();  // Bloqué

            return back()->with('success', 'Produit supprimé définitivement.');
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur lors de la suppression définitive  : '.$e->getMessage());
        }
    }

    public function handleProductAction(Request $request, string $action): RedirectResponse
    {
        try {
            $request->validate(['prod_id' => 'required|exists:products,id']);
            $product = Products::findOrFail($request->prod_id);
            $q = $request->q ? '?q='.$request->q : '';

            switch ($action) {
                case 'block':
                    $product->status = 2;
                    $message = 'Produit bloqué avec succès.';
                    break;
                case 'unblock':
                    $product->status = 1;
                    $message = 'Produit débloqué avec succès.';
                    break;
                case 'delete':
                    $product->status = 0;
                    $message = 'Produit supprimé avec succès.';
                    break;
                case 'restore':
                    $product->status = 1;
                    $message = 'Produit restauré avec succès.';
                    break;
                case 'erase':
                    $product->forceDelete();
                    $message = 'Produit supprimé définitivement.';

                    return redirect()->to('/super_admin_search'.$q)->with('success', $message);
                default:
                    return back()->with('error', 'Action invalide.');
            }

            $product->updated_at = now();
            $product->save();

            return redirect()->to('/super_admin_search'.$q)->with('success', $message);
        } catch (\Exception $e) {
            return back()->with('error', 'Erreur : '.$e->getMessage());
        }
    }

    public function createProduct(Request $request): RedirectResponse
    {
        $request->validate([
            'product_name' => 'required|string|max:255|min:2|unique:products,product_name',
            'single_price' => 'required|numeric|min:0',
            'detailed_price' => 'nullable|string|max:255',
            'post_scriptum' => 'nullable|string|max:1000',
            'branch_id' => 'required|exists:branches,id',
            'category_id' => 'required|exists:categories,id',
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
        try {
            Products::create([
                'product_name' => $request->product_name,
                'single_price' => $request->single_price,
                'detailed_price' => $request->detailed_price,
                'post_scriptum' => $request->post_scriptum,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
                'status' => 1,
            ]);

            return redirect()->route('super_admin_products')
                ->with('success', 'Produit créé avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la création : '.$e->getMessage()])
                ->withInput();
        }
    }

    public function updateProducts(Request $request): RedirectResponse
    {
        $id = $request->input('prod_id');
        $request->validate([
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
        try {
            $prod = Products::findOrFail($id);
            $prod->update([
                'product_name' => $request->product_name,
                'single_price' => $request->single_price,
                'detailed_price' => $request->detailed_price,
                'post_scriptum' => $request->post_scriptum,
                'branch_id' => $request->branch_id,
                'category_id' => $request->category_id,
            ]);

            return redirect()->route('super_admin_products')
                ->with('success', 'Produit modifié avec succès !');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erreur lors de la modification : '.$e->getMessage()])
                ->withInput();
        }
    }
}
