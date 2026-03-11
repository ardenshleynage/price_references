<?php

namespace App\View\Components\super_admin\search\users;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UsersModal extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.super_admin.search.users.users-modal');
    }
}
