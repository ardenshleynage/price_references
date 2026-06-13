<?php

namespace App\Livewire\Search;

use Illuminate\Contracts\View\View;
use Livewire\Attributes\Reactive;
use Livewire\Component;

class UsersModal extends Component
{
    #[Reactive]
    public ?array $selectedUser = null;

    #[Reactive]
    public int $userRole;

    public function closeUserModal(): void
    {
        $this->dispatch('search-close-user-modal');
    }

    public function blockUser(int $id): void
    {
        $this->dispatch('search-block-user', id: $id);
    }

    public function deleteUser(int $id): void
    {
        $this->dispatch('search-delete-user', id: $id);
    }

    public function unblockUser(int $id): void
    {
        $this->dispatch('search-unblock-user', id: $id);
    }

    public function restoreUser(int $id): void
    {
        $this->dispatch('search-restore-user', id: $id);
    }

    public function confirmEraseUser(int $id): void
    {
        $this->dispatch('search-confirm-erase-user', id: $id);
    }

    public function openEditUserModal(int $id): void
    {
        $this->dispatch('search-open-edit-user-modal', id: $id);
    }

    public function render(): View
    {
        return view('livewire.search.users-modal');
    }
}
