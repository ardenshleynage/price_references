<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class EraseConfirmModal extends Component
{
    #[Reactive]
    public ?int $eraseProductId = null;

    #[Reactive]
    public ?int $eraseCategoryId = null;

    #[Reactive]
    public ?int $eraseBranchId = null;

    #[Reactive]
    public ?int $eraseUserId = null;

    public function cancelErase(): void
    {
        $this->dispatch('search-cancel-erase');
    }

    public function erase(): void
    {
        $this->dispatch('search-erase');
    }

    public function render(): View
    {
        return view('livewire.search.erase-confirm-modal');
    }
}
