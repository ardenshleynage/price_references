<div>
    {{-- ALERT MESSAGES --}}
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert-success-message"
            style="margin-bottom: 15px;">
            {{ session('success') }}
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition class="alert-error-message"
            style="margin-bottom: 15px;">
            {{ session('error') }}
        </div>
    @endif

    {{-- TABS --}}
    @if ($userRole <= 2)
        <div class="container">
            <div class="tabs-wrapper">
                <div class="tabs">
                    <a href="#" wire:click.prevent="setFilter(null)"
                        class="tab {{ $statusFilter === null ? 'active' : '' }}">Tous</a>
                    <a href="#" wire:click.prevent="setFilter('active')"
                        class="tab {{ $statusFilter === 'active' ? 'active' : '' }}">Actif</a>
                    @if ($userRole <= 2)
                        <a href="#" wire:click.prevent="setFilter('blocked')"
                            class="tab {{ $statusFilter === 'blocked' ? 'active' : '' }}">Bloqué</a>
                    @endif
                    @if ($userRole <= 2)
                        <a href="#" wire:click.prevent="setFilter('deleted')"
                            class="tab {{ $statusFilter === 'deleted' ? 'active' : '' }}">Corbeille</a>
                    @endif
                    <span class="glider"></span>
                </div>
            </div>
        </div>
    @endif

    {{-- TABLE --}}
    @php $userData = array_map(fn($u) => ['id' => $u->id, 'username' => $u->username, 'email' => $u->email, 'role' => $u->role, 'status' => $u->status, 'last_time_connect_formatted' => $u->last_time_connect?->format('d/m/Y H:i') ?? 'Jamais', 'created_at_formatted' => $u->created_at->format('d/m/Y H:i'), 'updated_at_formatted' => $u->updated_at->format('d/m/Y H:i')], $users->items()); @endphp
    <livewire:users.table :users="$userData" :sort-field="$sortField" :sort-direction="$sortDirection" :user-role="$userRole"
        :status-filter="$statusFilter" />
    @if ($users->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $users->links('vendor.pagination.custom') }}
        </div>
    @endif

    {{-- USER DETAIL MODAL --}}
    @if ($showUserModal && $selectedUser)
        <livewire:users.detail-modal :selected-user="$selectedUser" :user-role="$userRole" :key="'detail-' . $selectedUser->id" />
    @endif
    {{-- CONFIRM ERASE MODAL --}}
    @if ($showConfirmErase)
        <livewire:users.confirm-erase-modal :key="'confirm-erase'" />
    @endif
    {{-- ADD USER MODAL --}}
    @if ($showAddModal)
        <livewire:users.add-modal :key="'add-modal'" />
    @endif
    {{-- EDIT USER MODAL --}}
    @if ($showEditModal && $selectedUser)
        <livewire:users.edit-modal :selected-user="$selectedUser" :key="'edit-' . $selectedUser->id" />
    @endif
</div>
