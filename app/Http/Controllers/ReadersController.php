<?php

namespace App\Http\Controllers;

use App\Helpers\DateTimeHelper;
use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class ReadersController extends Controller
{
    use DateTimeHelper;

    public function readersHome(): View
    {
        $dateTime = $this->getCurrentDateTime();
        $currentDate = $dateTime['date'];
        $currentTime = $dateTime['time'];
        $currentUserId = session('user_id');
        $totalCategories = Categories::where('status', 1)->count();
        $totalProducts = Products::where('status', 1)->count();
        $totalBranches = Branches::where('status', 1)->count();

        return view('readers.readers_home', compact('currentDate', 'currentTime', 'totalCategories', 'totalProducts', 'totalBranches'));
    }

    public function readersSearch(Request $request): View
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
                ->where('status', 1)
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
            $results['categories'] = Categories::where('status', 1)
                ->where('category_name', 'like', "%{$query}%")
                ->get()
                ->map(function ($category) {
                    $category->created_at_formatted = $category->created_at->format('d/m/Y H:i');
                    $category->updated_at_formatted = $category->updated_at->format('d/m/Y H:i');

                    return $category;
                });

            // Search in branches
            $results['branches'] = Branches::where('status', 1)
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

        return view('readers.readers_search', compact('currentDate', 'currentTime', 'results', 'query'));
    }

    public function readersProfile(): View
    {
        $loggedUser = User::find(session('user_id'));

        return view('readers.readers_profile', compact('loggedUser'));
    }
}
