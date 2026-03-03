<?php

namespace App\View\Components\users;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Modal_add_users extends Component
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
        return view('components.users.modal_add_users');
    }
}
