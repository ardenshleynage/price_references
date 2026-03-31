<?php

namespace App\View\Components\mobile\search\categories;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class CategoryDetailModal extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.mobile.search.categories.category-detail-modal');
    }
}
