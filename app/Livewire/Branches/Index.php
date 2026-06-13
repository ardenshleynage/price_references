<?php

namespace App\Livewire\Branches;

use App\Models\Branches;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

#[Layout('livewire.admin', params: ['current' => 'Branches'])]
#[Title('Branches')]
class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public ?Branches $selectedBranch = null;

    public bool $showDetailModal = false;

    public bool $showAddModal = false;

    public bool $showEditModal = false;

    public bool $showConfirmErase = false;

    public ?int $eraseBranchId = null;

    public int $userRole;

    public string $sortDirection = 'desc';

    public string $sortField = 'updated_at';

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
        $this->selectedBranch = Branches::findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedBranch = null;
    }

    public function block(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $branch = Branches::findOrFail($id);
        $branch->status = 2;
        $branch->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Branche bloquée avec succès.');
    }

    public function unblock(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Branche débloquée avec succès.');
    }

    public function delete(int $id): void
    {
        $branch = Branches::findOrFail($id);

        if ($this->userRole === 1) {
            $branch->status = 0;
        } elseif ($this->userRole === 2) {
            $branch->status = 0;
        } else {
            return;
        }

        $branch->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Branche supprimée avec succès.');
    }

    public function restore(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Branche restaurée avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseBranchId = $id;
        $this->showDetailModal = false;
        $this->showConfirmErase = true;
    }
    public function erase(): void
    {
        if ($this->eraseBranchId) {
            if ($this->userRole === 1) {
                Branches::findOrFail($this->eraseBranchId)->forceDelete();
            } else {
                $branch = Branches::findOrFail($this->eraseBranchId);
                $branch->status = 3;
                $branch->save();
            }
            $this->closeDetailModal();
            $this->showConfirmErase = false;
            $this->eraseBranchId = null;
            $this->tableRefreshKey++;
            session()->flash('success', 'Branche supprimée définitivement.');
        }
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseBranchId = null;
    }

    #[On('erase-branch')]
    public function handleEraseBranch(): void
    {
        $this->erase();
    }

    #[On('cancel-erase-branch')]
    public function handleCancelEraseBranch(): void
    {
        $this->cancelErase();
    }

    /**
     * @param mixed $message
     */
    #[On('branch-saved')]
    public function closeModals($message = ''): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedBranch = null;
        $this->tableRefreshKey++;

        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[On('open-add-modal')]
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
        $this->selectedBranch = Branches::findOrFail($id);
        $this->showEditModal = true;
        $this->showDetailModal = false;
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
        $query = Branches::orderBy($this->sortField, $this->sortDirection);

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

        $branches = $query->paginate(10);
        $branches->withPath(Livewire::originalUrl());

        return view('livewire.branches.index', [
            'branches' => $branches,
        ]);
    }
}
