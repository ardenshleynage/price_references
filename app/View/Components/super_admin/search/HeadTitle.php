<?php

namespace App\View\Components\super_admin\search;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class HeadTitle extends Component
{
    public $query;
    /**
     * Create a new component instance.
     * @param mixed $query
     */
    public function __construct($query = null)
    {
        $this->query = $query;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.super_admin.search.head-title');
    }
}
