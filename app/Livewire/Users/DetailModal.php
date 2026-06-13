<?php

namespace App\Livewire\Users;

use Illuminate\Contracts\View\View;
use Livewire\Component;
use App\Models\User;

class DetailModal extends Component
{
    public ?User $selectedUser = null;
    public int $userRole;

    public function mount(?User $selectedUser = null, int $userRole = 3): void
    {
        $this->selectedUser = $selectedUser;
        $this->userRole = $userRole;
    }

    public function render(): View
    {
        return view('livewire.users.detail-modal');
    }
}
