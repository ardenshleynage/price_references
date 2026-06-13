<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class CategoriesModal extends Component
{
    #[Reactive]
    public ?array $selectedCategory = null;

    #[Reactive]
    public int $userRole;

    #[Reactive]
    public string $categoryRoute;

    public function closeCategoryModal(): void
    {
        $this->dispatch('search-close-category-modal');
    }

    public function blockCategory(int $id): void
    {
        $this->dispatch('search-block-category', id: $id);
    }

    public function deleteCategory(int $id): void
    {
        $this->dispatch('search-delete-category', id: $id);
    }

    public function unblockCategory(int $id): void
    {
        $this->dispatch('search-unblock-category', id: $id);
    }

    public function restoreCategory(int $id): void
    {
        $this->dispatch('search-restore-category', id: $id);
    }

    public function confirmEraseCategory(int $id): void
    {
        $this->dispatch('search-confirm-erase-category', id: $id);
    }

    public function openEditCategoryModal(int $id): void
    {
        $this->dispatch('search-open-edit-category-modal', id: $id);
    }

    public function render(): View
    {
        return view('livewire.search.categories-modal');
    }
}
