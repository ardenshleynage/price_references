<?php

namespace App\Livewire\Categories;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddModal extends Component
{
    public function render(): View
    {
        return view('livewire.categories.add-modal');
    }
}
