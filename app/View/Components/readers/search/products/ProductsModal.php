<?php

namespace App\View\Components\readers\search\products;

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
        return view('components.readers.search.products.products-modal');
    }
}
