<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class AdminsController extends Controller
{
    use DateTimeHelper;

    public function adminsHome(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        /* $totalUsers = User::where('role', '!=', 1) */
        /*     ->where('id', '!=', $currentUserId) */
        /*     ->count(); */
        $totalCategories = Categories::whereIn('status', [1, 2])->count();
        $totalProducts = Products::whereIn('status', [1, 2])->count();
        $totalBranches = Branches::whereIn('status', [1, 2])->count();

        return view('admins.admins_home', compact('currentDate', 'currentTime', 'totalCategories', 'totalProducts', 'totalBranches'));
    }

    public function adminsSearch(Request $request): View
    {
        $query = $request->get('q', '');
        $results = [
            'products' => [],
            'categories' => [],
            'branches' => [],
        ];

        if (! empty($query)) {
            // Search in products (including post_scriptum, single_price, detailed_price)
            $results['products'] = Products::with(['branch', 'category'])
                ->whereIn('status', [1, 2])
                ->where(function ($q) use ($query) {
                    $q->where('product_name', 'like', "%{$query}%")
                        ->orWhere('post_scriptum', 'like', "%{$query}%")
                        ->orWhere('single_price', 'like', "%{$query}%")
                        ->orWhere('detailed_price', 'like', "%{$query}%");
                })
                ->get()
                ->map(function ($product) {
                    $product->created_at_formatted = $product->created_at->format('d/m/Y H:i');
                    $product->updated_at_formatted = $product->updated_at->format('d/m/Y H:i');

                    return $product;
                });

            // Search in categories
            $results['categories'] = Categories::whereIn('status', [1, 2])
                ->where('category_name', 'like', "%{$query}%")
                ->get()
                ->map(function ($category) {
                    $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                    $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                    return $category;
                });

            // Search in branches
            $results['branches'] = Branches::whereIn('status', [1, 2])
                ->where('branche_name', 'like', "%{$query}%")
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

        return view('admins.admins_search', compact('currentDate', 'currentTime', 'results', 'query'));
    }

    public function adminsProfile(): View
    {
        $loggedUser = User::find(session('user_id'));

        return view('admins.admins_profile', compact('loggedUser'));
    }
}
