<?php

namespace App\View\Components\super_admin\search\products;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductsModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.super_admin.search.products.products-modal');
    }
}
