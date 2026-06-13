<?php

namespace App\Livewire\Users;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\User;

class EditModal extends Component
{
    public ?User $selectedUser = null;

    public function mount(?User $selectedUser = null): void
    {
        $this->selectedUser = $selectedUser;
    }

    public function render(): View
    {
        return view('livewire.users.edit-modal');
    }
}
