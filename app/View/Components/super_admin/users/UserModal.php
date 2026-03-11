<?php

namespace App\View\Components\super_admin\users;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UserModal extends Component
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
        return view('components.super_admin.users.user-modal');
    }
}
