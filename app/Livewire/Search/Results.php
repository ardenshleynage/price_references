<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class Results extends Component
{
    #[Reactive]
    public array $results = [];

    #[Reactive]
    public string $query = '';

    #[Reactive]
    public int $userRole;

    #[Reactive]
    public string $productRoute;

    #[Reactive]
    public string $categoryRoute;

    #[Reactive]
    public string $branchRoute;

    public function showProduct(int $id): void
    {
        $this->dispatch('search-show-product', id: $id);
    }

    public function showCategory(int $id): void
    {
        $this->dispatch('search-show-category', id: $id);
    }

    public function showBranch(int $id): void
    {
        $this->dispatch('search-show-branch', id: $id);
    }

    public function showUser(int $id): void
    {
        $this->dispatch('search-show-user', id: $id);
    }

    public function render(): View
    {
        return view('livewire.search.results');
    }
}
