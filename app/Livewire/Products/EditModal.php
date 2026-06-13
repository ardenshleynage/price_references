<?php

namespace App\Livewire\Products;

use App\Models\Products;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditModal extends Component
{
    public ?Products $selectedProduct = null;

    public int $userRole;

    public function mount(?Products $selectedProduct = null, int $userRole = 3): void
    {
        $this->selectedProduct = $selectedProduct;
        $this->userRole = $userRole;
    }

    public function render(): View
    {
        return view('livewire.products.edit-modal');
    }
}
