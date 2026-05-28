<?php

namespace App\Livewire\Branches;

use App\Models\Branches;
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
        if ($this->userRole !== 1) {
            return;
        }

        $branch = Branches::findOrFail($id);
        $branch->status = 2;
        $branch->save();
        $this->closeDetailModal();
        session()->flash('success', 'Branche bloquée avec succès.');
    }

    public function unblock(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }

        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeDetailModal();
        session()->flash('success', 'Branche débloquée avec succès.');
    }

    public function delete(int $id): void
    {
        $branch = Branches::findOrFail($id);

        if ($this->userRole === 1) {
            $branch->status = 0;
        } elseif ($this->userRole === 2) {
            $branch->status = 2;
        } else {
            return;
        }

        $branch->save();
        $this->closeDetailModal();
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
        session()->flash('success', 'Branche restaurée avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseBranchId = $id;
        $this->showConfirmErase = true;
    }

    public function erase(): void
    {
        if ($this->eraseBranchId) {
            Branches::findOrFail($this->eraseBranchId)->forceDelete();
            $this->closeDetailModal();
            $this->showConfirmErase = false;
            $this->eraseBranchId = null;
            session()->flash('success', 'Branche supprimée définitivement.');
        }
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseBranchId = null;
    }

    #[On('branch-saved')]
    public function closeModals($message = ''): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedBranch = null;

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
        $this->selectedBranch = Branches::findOrFail($id);
        $this->showEditModal = true;
    }

    public function render()
    {
        $query = Branches::orderBy('updated_at', 'desc');

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

        $branches = $query->paginate(10);
        $branches->withPath(Livewire::originalUrl());

        return view('livewire.branches.index', [
            'branches' => $branches,
        ]);
    }
}
