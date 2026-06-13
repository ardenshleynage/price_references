<div>
    {{-- ALERTS --}}
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
    @php $categoryData = array_map(fn($c) => ['id' => $c->id, 'category_name' => $c->category_name, 'status' => $c->status, 'created_at_formatted' => $c->created_at->format('d/m/Y H:i'), 'updated_at_formatted' => $c->updated_at->format('d/m/Y H:i')], $categories->items()); @endphp
    <livewire:categories.table :categories="$categoryData" :sort-field="$sortField" :sort-direction="$sortDirection" :user-role="$userRole"
        :status-filter="$statusFilter" />
    @if ($categories->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $categories->links('vendor.pagination.custom') }}
        </div>
    @endif
    {{-- DETAIL MODAL --}}
    @if ($showDetailModal && $selectedCategory)
        <livewire:categories.detail-modal :selected-category="$selectedCategory" :user-role="$userRole" :key="'detail-' . $selectedCategory->id" />
    @endif
    {{-- CONFIRM ERASE MODAL --}}
    @if ($showConfirmErase)
        <livewire:categories.confirm-erase-modal :key="'confirm-erase'" />
    @endif
    {{-- ADD MODAL --}}
    @if ($showAddModal)
        <livewire:categories.add-modal :key="'add-modal'" />
    @endif
    {{-- EDIT MODAL --}}
    @if ($showEditModal && $selectedCategory)
        <livewire:categories.edit-modal :selected-category="$selectedCategory" :key="'edit-' . $selectedCategory->id" />
    @endif
</div>
