<?php

namespace App\Livewire\Branches;

use App\Models\Branches;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditModal extends Component
{
    public ?Branches $selectedBranch = null;

    public function mount(?Branches $selectedBranch = null): void
    {
        $this->selectedBranch = $selectedBranch;
    }

    public function render(): View
    {
        return view('livewire.branches.edit-modal');
    }
}
