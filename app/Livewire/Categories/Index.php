<?php

namespace App\Livewire\Categories;

use App\Models\Categories;
use Illuminate\Contracts\View\View;
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

    public string $sortField = 'updated_at';

    public string $sortDirection = 'desc';

    public int $tableRefreshKey = 0;

    public function mount(): void
    {
        $this->userRole = (int) Auth::user()->role;
    }

    public function setFilter(?string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
        $this->tableRefreshKey++;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
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
        if ($this->userRole > 2) {
            return;
        }

        $category = Categories::findOrFail($id);
        $category->status = 2;
        $category->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Catégorie bloquée avec succès.');
    }

    public function unblock(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Catégorie débloquée avec succès.');
    }

    public function delete(int $id): void
    {
        $category = Categories::findOrFail($id);

        if ($this->userRole === 1) {
            $category->status = 0;
        } elseif ($this->userRole === 2) {
            $category->status = 0;
        } else {
            return;
        }

        $category->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
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
        $this->tableRefreshKey++;
        session()->flash('success', 'Catégorie restaurée avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseCategoryId = $id;
        $this->showDetailModal = false;
        $this->showConfirmErase = true;
    }

    public function erase(): void
    {
        if ($this->eraseCategoryId) {
            if ($this->userRole === 1) {
                Categories::findOrFail($this->eraseCategoryId)->forceDelete();
            } else {
                $category = Categories::findOrFail($this->eraseCategoryId);
                $category->status = 3;
                $category->save();
            }
            $this->closeDetailModal();
            $this->showConfirmErase = false;
            $this->eraseCategoryId = null;
            $this->tableRefreshKey++;
            session()->flash('success', 'Catégorie supprimée définitivement.');
        }
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseCategoryId = null;
    }
    /**
     * @param mixed $message
     */
    #[On('category-saved')]
    public function closeModals($message = ''): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedCategory = null;
        $this->tableRefreshKey++;

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
        $this->showDetailModal = false;
        $this->showEditModal = true;
    }

    #[On('open-add-modal')]
    public function handleOpenAddModal(): void
    {
        $this->openAddModal();
    }
    /**
     * @param mixed $filter
     */
    #[On('tab-filter-set')]
    public function handleTabFilterSet($filter): void
    {
        $this->setFilter($filter);
    }
    #[On('tabs-set-filter')]
    public function handleTabsSetFilter($filter): void
    {
        $this->setFilter($filter);
    }

    /**
     * @param mixed $field
     */
    #[On('table-sort-by')]
    public function handleTableSortBy($field): void
    {
        $this->sortBy($field);
    }
    /**
     * @param mixed $id
     */
    #[On('table-view-detail')]
    public function handleTableViewDetail($id): void
    {
        $this->viewDetail($id);
    }

    public function render(): View
    {
        $query = Categories::orderBy($this->sortField, $this->sortDirection);

        if ($this->userRole === 2) {
            $query->whereIn('status', [0, 1, 2]);
        } elseif ($this->userRole === 3) {
            $query->where('status', 1);
        }

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('status', 2);
        } elseif ($this->statusFilter === 'deleted') {
            $query->where('status', 0);
        } elseif ($this->statusFilter === 'deleted_by_admin') {
            $query->where('status', 3);
        }

        $categories = $query->paginate(10);
        $categories->withPath(Livewire::originalUrl());

        return view('livewire.categories.index', [
            'categories' => $categories,
        ]);
    }
}
