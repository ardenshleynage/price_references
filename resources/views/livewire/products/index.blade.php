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
    @php $productData = array_map(fn($p) => ['id' => $p->id, 'product_name' => $p->product_name, 'single_price' => $p->single_price, 'status' => $p->status, 'created_at_formatted' => $p->created_at->format('d/m/Y H:i'), 'updated_at_formatted' => $p->updated_at->format('d/m/Y H:i')], $products->items()); @endphp
    <livewire:products.table :products="$productData" :sort-field="$sortField" :sort-direction="$sortDirection" :user-role="$userRole"
        :status-filter="$statusFilter" />
    @if ($products->hasPages())
        <div class="pagination-wrapper" style="margin-top: 20px; width: 100%; clear: both;">
            {{ $products->links('vendor.pagination.custom') }}
        </div>
    @endif
    {{-- DETAIL MODAL --}}
    @if ($showDetailModal && $selectedProduct)
        <livewire:products.detail-modal :selected-product="$selectedProduct" :user-role="$userRole" :key="'detail-' . $selectedProduct->id" />
    @endif
    {{-- CONFIRM ERASE MODAL --}}
    @if ($showConfirmErase)
        <livewire:products.confirm-erase-modal :key="'confirm-erase'" />
    @endif
    {{-- ADD MODAL --}}
    @if ($showAddModal)
        <livewire:products.add-modal :key="'add-modal'" :user-role="$userRole" />
    @endif
    {{-- EDIT MODAL --}}
    @if ($showEditModal && $selectedProduct)
        <livewire:products.edit-modal :selected-product="$selectedProduct" :user-role="$userRole" :key="'edit-' . $selectedProduct->id" />
    @endif
</div>
