<?php

namespace App\View\Components\super_admin\categories;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalAddCategories extends Component
{
    public bool $superAdminExists;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $superAdminExists = false)
    {
        //
        $this->superAdminExists = $superAdminExists;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.super_admin.categories.modal-add-categories');
    }
}
