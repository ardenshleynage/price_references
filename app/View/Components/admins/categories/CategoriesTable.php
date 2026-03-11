<?php

namespace App\View\Components\admins\categories;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class CategoriesTable extends Component
{
    public string $emptyMessage;
    public Collection $categories;

    /**
     * Create a new component instance.

     @param \Illuminate\Database\Eloquent\Collection $categories

     * @param string $emptyMessage
     */
    public function __construct(Collection $categories, string $emptyMessage = 'Aucune catégorie enregistrée')
    {
        $this->categories = $categories;
        $this->emptyMessage = $emptyMessage;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.categories.categories-table');
    }
}
