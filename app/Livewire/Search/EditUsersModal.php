<?php

namespace App\Livewire\Search;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditUsersModal extends Component
{
    public ?User $selectedUser = null;

    public function closeEditUserModal(): void
    {
        $this->dispatch('search-close-edit-user-modal');
    }

    public function render(): View
    {
        return view('livewire.search.edit-users-modal');
    }
}
