<?php

namespace App\View\Components\mobile\search\categories;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class EditCategoryModal extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.mobile.search.categories.edit-category-modal');
    }
}
