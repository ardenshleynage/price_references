<?php

namespace App\Livewire\Categories;

use App\Models\Categories;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditModal extends Component
{
    public ?Categories $selectedCategory = null;

    public function mount(?Categories $selectedCategory = null): void
    {
        $this->selectedCategory = $selectedCategory;
    }

    public function render(): View
    {
        return view('livewire.categories.edit-modal');
    }
}
