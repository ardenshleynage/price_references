<?php

namespace App\Livewire\Products;

use Livewire\Component;

class AddModal extends Component
{
    public int $userRole;

    public function mount(int $userRole = 3): void
    {
        $this->userRole = $userRole;
    }

    public function render()
    {
        return view('livewire.products.add-modal');
    }
}
