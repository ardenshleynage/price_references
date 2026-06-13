<?php

namespace App\Livewire\Search;

use App\Models\Branches;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditBranchesModal extends Component
{
    public ?Branches $selectedBranch = null;

    public function closeEditBranchModal(): void
    {
        $this->dispatch('search-close-edit-branch-modal');
    }

    public function render(): View
    {
        return view('livewire.search.edit-branches-modal');
    }
}
