<?php

namespace App\Livewire\Categories;

use App\Models\Categories;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class DetailModal extends Component
{
    public ?Categories $selectedCategory = null;
    public int $userRole;

    public function mount(?Categories $selectedCategory = null, int $userRole = 3): void
    {
        $this->selectedCategory = $selectedCategory;
        $this->userRole = $userRole;
    }

    public function render(): View
    {
        return view('livewire.categories.detail-modal');
    }
}
