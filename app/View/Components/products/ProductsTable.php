<?php

namespace App\View\Components\products;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class ProductsTable extends Component
{
    public string $emptyMessage;
    public Collection $products;

    /**
     * Create a new component instance.
       @param \Illuminate\Database\Eloquent\Collection $products
     *
     */
    public function __construct(Collection $products, string $emptyMessage = 'Aucun produit enregistrée')
    {
        $this->products = $products;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.products.products-table');
    }
}
