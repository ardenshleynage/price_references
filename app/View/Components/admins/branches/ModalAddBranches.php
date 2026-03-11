<?php

namespace App\View\Components\admins\branches;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ModalAddBranches extends Component
{
    public bool $adminsExists;

    /**
     * Create a new component instance.
     */
    public function __construct(bool $adminsExists = false)
    {
        $this->adminsExists = $adminsExists;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.branches.modal-add-branches');
    }
}
