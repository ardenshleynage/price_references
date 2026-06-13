<?php

namespace App\Livewire\Branches;

use App\Models\Branches;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DetailModal extends Component
{
    public ?Branches $selectedBranch = null;
    public int $userRole;

    public function mount(?Branches $selectedBranch = null, int $userRole = 3): void
    {
        $this->selectedBranch = $selectedBranch;
        $this->userRole = $userRole;
    }

    public function render(): View
    {
        return view('livewire.branches.detail-modal');
    }
}
