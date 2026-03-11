<?php

namespace App\View\Components\super_admin\users;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;
use Illuminate\Database\Eloquent\Collection;

class UsersTable extends Component
{
    public string $emptyMessage;
    public Collection $users;

    /**
     * Create a new component instance.
     *
     * @param \Illuminate\Database\Eloquent\Collection $users
     * @param string $emptyMessage
     */
    public function __construct(Collection $users, string $emptyMessage = 'Aucun utilisateur enregistré')
    {
        //
        $this->users = $users;
        $this->emptyMessage = $emptyMessage;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.super_admin.users.users-table');
    }
}
