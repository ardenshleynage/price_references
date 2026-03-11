<?php

namespace App\View\Components\admins\search\products;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ProductsResults extends Component
{
    public $results;

    /**
     * Create a new component instance.
     * @param mixed $results
     */
    public function __construct($results = [])
    {
        $this->results = $results;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.search.products.products-results');
    }
}
