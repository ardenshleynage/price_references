<?php

namespace App\Livewire\Categories;

use App\Models\Categories;
use Illuminate\Contracts\View\View;
use Livewire\Component;

class Form extends Component
{
    public string $mode = 'create';

    public ?int $categoryId = null;

    public string $category_name = '';

    protected function rules(): array
    {
        $unique = 'unique:categories,category_name';

        if ($this->mode === 'edit' && $this->categoryId) {
            $unique .= ',' . $this->categoryId;
        }

        return [
            'category_name' => 'required|string|max:255|min:2|' . $unique,
        ];
    }

    protected $messages = [
        'category_name.required' => 'Le nom de la catégorie est obligatoire.',
        'category_name.string' => 'Le nom de la catégorie doit être une chaîne de caractères.',
        'category_name.max' => 'Le nom de la catégorie doit contenir au maximum 255 caractères.',
        'category_name.min' => 'Le nom de la catégorie doit contenir au moins 2 caractères.',
        'category_name.unique' => 'Cette catégorie existe déjà.',
    ];

    public function mount(?int $categoryId = null): void
    {
        if ($categoryId) {
            $this->mode = 'edit';
            $this->categoryId = $categoryId;
            $category = Categories::findOrFail($categoryId);
            $this->category_name = $category->category_name;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->mode === 'create') {
            Categories::create([
                'category_name' => $this->category_name,
                'status' => 1,
            ]);

            $this->dispatch('category-saved', message: 'Catégorie créée avec succès !');
        } else {
            Categories::findOrFail($this->categoryId)->update([
                'category_name' => $this->category_name,
            ]);

            $this->dispatch('category-saved', message: 'Catégorie modifiée avec succès !');
        }
    }

    public function render(): View
    {
        return view('livewire.categories.form');
    }
}
