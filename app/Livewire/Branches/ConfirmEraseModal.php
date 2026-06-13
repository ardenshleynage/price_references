<?php

namespace App\Livewire\Branches;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConfirmEraseModal extends Component
{
    public function erase(): void
    {
        $this->dispatch('erase-branch');
    }

    public function cancelErase(): void
    {
        $this->dispatch('cancel-erase-branch');
    }

    public function render(): View
    {
        return view('livewire.branches.confirm-erase-modal');
    }
}
