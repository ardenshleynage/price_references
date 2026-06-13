<?php

namespace App\Livewire\Search;

use App\Models\Products;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditProductsModal extends Component
{
    public ?Products $selectedProduct = null;

    public function closeEditProductModal(): void
    {
        $this->dispatch('search-close-edit-product-modal');
    }

    public function render(): View
    {
        return view('livewire.search.edit-products-modal');
    }
}
