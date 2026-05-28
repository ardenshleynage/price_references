<?php

namespace App\Livewire\Branches;

use App\Models\Branches;
use Livewire\Component;

class Form extends Component
{
    public string $mode = 'create';

    public ?int $branchId = null;

    public string $branche_name = '';

    protected function rules(): array
    {
        $unique = 'unique:branches,branche_name';

        if ($this->mode === 'edit' && $this->branchId) {
            $unique .= ','.$this->branchId;
        }

        return [
            'branche_name' => 'required|string|max:255|min:2|'.$unique,
        ];
    }

    protected $messages = [
        'branche_name.required' => 'Le nom de la branche est obligatoire.',
        'branche_name.string' => 'Le nom de la branche doit être une chaîne de caractères.',
        'branche_name.max' => 'Le nom de la branche doit contenir au maximum 255 caractères.',
        'branche_name.min' => 'Le nom de la branche doit contenir au moins 2 caractères.',
        'branche_name.unique' => 'Cette branche existe déjà.',
    ];

    public function mount(?int $branchId = null): void
    {
        if ($branchId) {
            $this->mode = 'edit';
            $this->branchId = $branchId;
            $branch = Branches::findOrFail($branchId);
            $this->branche_name = $branch->branche_name;
        }
    }

    public function save(): void
    {
        $this->validate();

        if ($this->mode === 'create') {
            Branches::create([
                'branche_name' => $this->branche_name,
                'status' => 1,
            ]);

            $this->dispatch('branch-saved', message: 'Branche créée avec succès !');
        } else {
            Branches::findOrFail($this->branchId)->update([
                'branche_name' => $this->branche_name,
            ]);

            $this->dispatch('branch-saved', message: 'Branche modifiée avec succès !');
        }
    }

    public function render()
    {
        return view('livewire.branches.form');
    }
}
