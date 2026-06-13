<?php

namespace App\Livewire\Categories;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Table extends Component
{
    #[Reactive]
    public $categories = [];

    #[Reactive]
    public string $sortField = 'updated_at';

    #[Reactive]
    public string $sortDirection = 'desc';

    #[Reactive]
    public int $userRole;

    #[Reactive]
    public ?string $statusFilter = null;

    public function sortBy(string $field): void
    {
        $this->dispatch('table-sort-by', field: $field);
    }

    public function viewDetail(int $id): void
    {
        $this->dispatch('table-view-detail', id: $id);
    }

    public function render(): View
    {
        return view('livewire.categories.table');
    }
}
