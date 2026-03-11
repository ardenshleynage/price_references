<?php

namespace App\View\Components\admins\search;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class NoResults extends Component
{
    public $query;
    public $results;

    /**
     * Create a new component instance.
     * @param mixed $query
     * @param mixed $results
     */
    public function __construct($query = null, $results = [])
    {
        $this->query = $query;
        $this->results = $results;
    }
    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.search.no-results');
    }
}
