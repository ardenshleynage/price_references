<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class BranchesModal extends Component
{
    #[Reactive]
    public ?array $selectedBranch = null;

    #[Reactive]
    public int $userRole;

    #[Reactive]
    public string $branchRoute;

    public function closeBranchModal(): void
    {
        $this->dispatch('search-close-branch-modal');
    }

    public function blockBranch(int $id): void
    {
        $this->dispatch('search-block-branch', id: $id);
    }

    public function deleteBranch(int $id): void
    {
        $this->dispatch('search-delete-branch', id: $id);
    }

    public function unblockBranch(int $id): void
    {
        $this->dispatch('search-unblock-branch', id: $id);
    }

    public function restoreBranch(int $id): void
    {
        $this->dispatch('search-restore-branch', id: $id);
    }

    public function confirmEraseBranch(int $id): void
    {
        $this->dispatch('search-confirm-erase-branch', id: $id);
    }

    public function openEditBranchModal(int $id): void
    {
        $this->dispatch('search-open-edit-branch-modal', id: $id);
    }

    public function render(): View
    {
        return view('livewire.search.branches-modal');
    }
}
