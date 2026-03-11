<?php

namespace App\View\Components\admins\branches;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class BranchesTable extends Component
{
    public string $emptyMessage;
    public Collection $branches;


    /**
     * Create a new component instance.

     @param \Illuminate\Database\Eloquent\Collection $branches

     * @param string $emptyMessage
     */

    public function __construct(Collection $branches, string $emptyMessage = 'Aucune branche enregistrée')
    {
        //
        $this->branches = $branches;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.branches.branches-table');
    }
}
