<?php

namespace App\Livewire\Users;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConfirmEraseModal extends Component
{
    public function erase(): void
    {
        $this->dispatch('erase-user');
    }

    public function cancelErase(): void
    {
        $this->dispatch('cancel-erase-user');
    }

    public function render(): View
    {
        return view('livewire.users.confirm-erase-modal');
    }
}
