<?php

namespace App\Livewire\Categories;

use App\Models\Categories;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

#[Layout('livewire.admin', params: ['current' => 'Categories'])]
#[Title('Catégories')]
class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public ?Categories $selectedCategory = null;

    public bool $showDetailModal = false;

    public bool $showAddModal = false;

    public bool $showEditModal = false;

    public bool $showConfirmErase = false;

    public ?int $eraseCategoryId = null;

    public int $userRole;

    public function mount(): void
    {
        $this->userRole = (int) Auth::user()->role;
    }

    public function setFilter(?string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function viewDetail(int $id): void
    {
        $this->selectedCategory = Categories::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedCategory = null;
    }

    public function block(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }

        $category = Categories::findOrFail($id);
        $category->status = 2;
        $category->save();
        $this->closeDetailModal();
        session()->flash('success', 'Catégorie bloquée avec succès.');
    }

    public function unblock(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }

        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeDetailModal();
        session()->flash('success', 'Catégorie débloquée avec succès.');
    }

    public function delete(int $id): void
    {
        $category = Categories::findOrFail($id);

        if ($this->userRole === 1) {
            $category->status = 0;
        } elseif ($this->userRole === 2) {
            $category->status = 2;
        } else {
            return;
        }

        $category->save();
        $this->closeDetailModal();
        session()->flash('success', 'Catégorie supprimée avec succès.');
    }

    public function restore(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeDetailModal();
        session()->flash('success', 'Catégorie restaurée avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseCategoryId = $id;
        $this->showConfirmErase = true;
    }

    public function erase(): void
    {
        if ($this->eraseCategoryId) {
            Categories::findOrFail($this->eraseCategoryId)->forceDelete();
            $this->closeDetailModal();
            $this->showConfirmErase = false;
            $this->eraseCategoryId = null;
            session()->flash('success', 'Catégorie supprimée définitivement.');
        }
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseCategoryId = null;
    }

    #[On('category-saved')]
    public function closeModals($message = ''): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedCategory = null;

        if ($message) {
            session()->flash('success', $message);
        }
    }

    public function openAddModal(): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $this->showAddModal = true;
    }

    public function openEditModal(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $this->selectedCategory = Categories::findOrFail($id);
        $this->showEditModal = true;
    }

    public function render()
    {
        $query = Categories::orderBy('updated_at', 'desc');

        if ($this->userRole === 2) {
            $query->whereIn('status', [1, 2]);
        } elseif ($this->userRole === 3) {
            $query->where('status', 1);
        }

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('status', 2);
        } elseif ($this->statusFilter === 'deleted') {
            $query->where('status', 0);
        }

        $categories = $query->paginate(10);
        $categories->withPath(Livewire::originalUrl());

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}
