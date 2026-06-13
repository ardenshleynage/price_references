<?php

namespace App\Livewire\Branches;

use Illuminate\Contracts\View\View;
use Livewire\Component;

class AddModal extends Component
{
    public function render(): View
    {
        return view('livewire.branches.add-modal');
    }
}
