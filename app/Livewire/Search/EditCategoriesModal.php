<?php

namespace App\Livewire\Search;

use App\Models\Categories;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class EditCategoriesModal extends Component
{
    public ?Categories $selectedCategory = null;

    public function closeEditCategoryModal(): void
    {
        $this->dispatch('search-close-edit-category-modal');
    }

    public function render(): View
    {
        return view('livewire.search.edit-categories-modal');
    }
}
