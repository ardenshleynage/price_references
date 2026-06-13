<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class ProductsModal extends Component
{
    #[Reactive]
    public ?array $selectedProduct = null;

    #[Reactive]
    public int $userRole;

    #[Reactive]
    public string $productRoute;

    public function closeProductModal(): void
    {
        $this->dispatch('search-close-product-modal');
    }

    public function blockProduct(int $id): void
    {
        $this->dispatch('search-block-product', id: $id);
    }

    public function deleteProduct(int $id): void
    {
        $this->dispatch('search-delete-product', id: $id);
    }

    public function unblockProduct(int $id): void
    {
        $this->dispatch('search-unblock-product', id: $id);
    }

    public function restoreProduct(int $id): void
    {
        $this->dispatch('search-restore-product', id: $id);
    }

    public function confirmEraseProduct(int $id): void
    {
        $this->dispatch('search-confirm-erase-product', id: $id);
    }

    public function openEditProductModal(int $id): void
    {
        $this->dispatch('search-open-edit-product-modal', id: $id);
    }

    public function render(): View
    {
        return view('livewire.search.products-modal');
    }
}
