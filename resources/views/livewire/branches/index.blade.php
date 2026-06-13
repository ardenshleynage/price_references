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
                    @if ($userRole === 1)
                        <a href="#" wire:click.prevent="setFilter('deleted_by_admin')"
                            class="tab {{ $statusFilter === 'deleted_by_admin' ? 'active' : '' }}">Supprimé par
                            l'admin</a>
                    @endif
                    <span class="glider"></span>
                </div>
            </div>
        </div>
    @endif
    {{-- TABLE --}}
    @php $branchData = array_map(fn($b) => ['id' => $b->id, 'branche_name' => $b->branche_name, 'status' => $b->status, 'created_at_formatted' => $b->created_at->format('d/m/Y H:i'), 'updated_at_formatted' => $b->updated_at->format('d/m/Y H:i')], $branches->items()); @endphp
    <livewire:branches.table :branches="$branchData" :sort-field="$sortField" :sort-direction="$sortDirection" :user-role="$userRole"
        :status-filter="$statusFilter" />
    @if ($branches->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $branches->links('vendor.pagination.custom') }}
        </div>
    @endif
    {{-- DETAIL MODAL --}}
    @if ($showDetailModal && $selectedBranch)
        <livewire:branches.detail-modal :selected-branch="$selectedBranch" :user-role="$userRole" :key="'detail-' . $selectedBranch->id" />
    @endif
    {{-- CONFIRM ERASE MODAL --}}
    @if ($showConfirmErase && $eraseBranchId)
        <livewire:branches.confirm-erase-modal :key="'confirm-erase'" />
    @endif
    {{-- ADD MODAL --}}
    @if ($showAddModal)
        <livewire:branches.add-modal :key="'add-modal'" />
    @endif
    {{-- EDIT MODAL --}}
    @if ($showEditModal && $selectedBranch)
        <livewire:branches.edit-modal :selected-branch="$selectedBranch" :key="'edit-' . $selectedBranch->id" />
    @endif
</div>
