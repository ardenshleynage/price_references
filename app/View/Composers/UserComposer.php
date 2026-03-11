<?php

namespace App\View\Composers;

use Illuminate\Support\Facades\Session;
use Illuminate\View\View;

class UserComposer
{
    public function compose(View $view): void
    {
        $userId = Session::get('user_id');

        if ($userId) {
            $user = \App\Models\User::find($userId);
            $view->with('loggedUser', $user);
            $view->with('userRole', $user->role ?? null);
        } else {
            $view->with('loggedUser', null);
            $view->with('userRole', null);
        }

        // Partager l'état du sidebar avec toutes les vues
        $view->with('sidebarCollapsed', Session::get('sidebar_collapsed', false));
    }
}
