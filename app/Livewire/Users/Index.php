<?php

namespace App\Livewire\Users;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

#[Layout('livewire.admin', params: ['current' => 'Utilisateurs'])]
#[Title('Utilisateurs')]
class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public ?User $selectedUser = null;

    public bool $showUserModal = false;

    public bool $showAddModal = false;

    public bool $showEditModal = false;

    public bool $showConfirmErase = false;

    public ?int $eraseUserId = null;

    public function mount(): void
    {
        if ((int) Auth::user()->role !== 1) {
            abort(403);
        }
    }

    public function setFilter(?string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
    }

    public function viewUser(int $id): void
    {
        $this->selectedUser = User::findOrFail($id);
        $this->showUserModal = true;
    }

    public function closeUserModal(): void
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    #[On('user-saved')]
    public function closeModals($message = ''): void
    {
        $this->showUserModal = false;
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedUser = null;

        if ($message) {
            session()->flash('success', $message);
        }
    }

    public function openAddModal(): void
    {
        $this->showAddModal = true;
    }

    public function openEditModal(int $id): void
    {
        $this->selectedUser = User::findOrFail($id);
        $this->showEditModal = true;
    }

    public function block(int $id): void
    {
        $user = User::findOrFail($id);
        $user->status = 2;
        $user->save();
        $this->closeUserModal();
        session()->flash('success', 'Utilisateur bloqué avec succès.');
    }

    public function unblock(int $id): void
    {
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();
        $this->closeUserModal();
        session()->flash('success', 'Utilisateur débloqué avec succès.');
    }

    public function delete(int $id): void
    {
        $user = User::findOrFail($id);
        $user->status = 0;
        $user->save();
        $this->closeUserModal();
        session()->flash('success', 'Utilisateur supprimé avec succès.');
    }

    public function restore(int $id): void
    {
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();
        $this->closeUserModal();
        session()->flash('success', 'Utilisateur restauré avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseUserId = $id;
        $this->showConfirmErase = true;
    }

    public function erase(): void
    {
        if ($this->eraseUserId) {
            User::findOrFail($this->eraseUserId)->forceDelete();
            $this->closeUserModal();
            $this->showConfirmErase = false;
            $this->eraseUserId = null;
            session()->flash('success', 'Utilisateur supprimé définitivement.');
        }
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseUserId = null;
    }

    public function updatingShowAddModal(bool $value): void
    {
        if (! $value) {
            $this->resetPage();
        }
    }

    public function render()
    {
        $currentUserId = Auth::id();

        $query = User::where('role', '!=', 1)
            ->where('id', '!=', $currentUserId)
            ->orderBy('updated_at', 'desc');

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('status', 2);
        } elseif ($this->statusFilter === 'deleted') {
            $query->where('status', 0);
        }

        $users = $query->paginate(10);
        $users->withPath(Livewire::originalUrl());

        return view('livewire.users.index', [
            'users' => $users,
        ]);
    }
}
