<?php

namespace App\View\Components\products;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\View\Component;

class ModalAddProducts extends Component
{
    public bool $superAdminExists;
    public Collection $branches;
    public Collection $categories;

    /**
     * Create a new component instance.
     * @param Collection<array-key,Model> $branches
     * @param Collection<array-key,Model> $categories
     */
    public function __construct(bool $superAdminExists = false, Collection $branches, Collection $categories)
    {
        $this->superAdminExists = $superAdminExists;
        $this->branches = $branches;
        $this->categories = $categories;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.products.modal-add-products');
    }
}
