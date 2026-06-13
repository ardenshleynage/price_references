<?php

namespace App\Livewire\Categories;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class ConfirmEraseModal extends Component
{
    public function render(): View
    {
        return view('livewire.categories.confirm-erase-modal');
    }
}
