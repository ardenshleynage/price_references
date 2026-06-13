<div>
    @if (session('success'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms
            style="background: #d4edda; color: #155724; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('success') }}</span>
            <button @click="show = false"
                style="background: none; border: none; font-size: 20px; cursor: pointer; color: #155724;">&times;</button>
        </div>
    @endif
    @if (session('error'))
        <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 5000)" x-show="show" x-transition.duration.500ms
            style="background: #f8d7da; color: #721c24; padding: 12px 16px; border-radius: 6px; margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
            <span>{{ session('error') }}</span>
            <button @click="show = false"
                style="background: none; border: none; font-size: 20px; cursor: pointer; color: #721c24;">&times;</button>
        </div>
    @endif
    <div style="margin-bottom: 24px;">
        <div style="display: flex; gap: 12px; align-items: center;">
            <input wire:model.live.debounce.300ms="query" type="search"
                placeholder="Rechercher un produit, une catégorie, une branche..."
                style="flex: 1; padding: 14px 16px; border: 1px solid #ddd; border-radius: 8px; font-size: 15px; background: #fff; color: #333; outline: none;">
            <i class='bx bx-search' style="font-size: 24px; color: #888;"></i>
        </div>
    </div>

    @php
        $resultsArray = [];
        if (!empty($results)) {
            $resultsArray['products'] = ($results['products'] ?? collect())->map(fn($p) => [
                'id' => $p->id,
                'product_name' => $p->product_name,
                'single_price' => $p->single_price,
                'branch_name' => $p->branch_name,
                'category_name' => $p->category_name,
                'status' => $p->status,
            ])->toArray();

            $resultsArray['categories'] = ($results['categories'] ?? collect())->map(fn($c) => [
                'id' => $c->id,
                'category_name' => $c->category_name,
                'status' => $c->status,
            ])->toArray();

            $resultsArray['branches'] = ($results['branches'] ?? collect())->map(fn($b) => [
                'id' => $b->id,
                'branche_name' => $b->branche_name,
                'status' => $b->status,
            ])->toArray();

            $resultsArray['users'] = ($results['users'] ?? collect())->map(fn($u) => [
                'id' => $u->id,
                'username' => $u->username,
                'email' => $u->email,
                'role' => $u->role,
                'status' => $u->status,
            ])->toArray();
        }
    @endphp
    <livewire:search.results :results="$resultsArray" :query="$query" :user-role="$userRole"
        :product-route="$productRoute" :category-route="$categoryRoute" :branch-route="$branchRoute" />

    {{-- Product Modal --}}
    @php
        $selectedProductArray = $selectedProduct ? [
            'id' => $selectedProduct->id,
            'product_name' => $selectedProduct->product_name,
            'single_price' => $selectedProduct->single_price,
            'detailed_price' => $selectedProduct->detailed_price,
            'post_scriptum' => $selectedProduct->post_scriptum,
            'branch_name' => $selectedProduct->branch_name,
            'category_name' => $selectedProduct->category_name,
            'created_at_formatted' => $selectedProduct->created_at_formatted,
            'status' => $selectedProduct->status,
        ] : null;
    @endphp
    @if ($showProductModal && $selectedProductArray)
        <livewire:search.products-modal :selected-product="$selectedProductArray" :user-role="$userRole"
            :product-route="$productRoute" :key="'product-detail'" />
    @endif

    {{-- Category Modal --}}
    @php
        $selectedCategoryArray = $selectedCategory ? [
            'id' => $selectedCategory->id,
            'category_name' => $selectedCategory->category_name,
            'created_at_formatted' => $selectedCategory->created_at_formatted,
            'updated_at_formatted' => $selectedCategory->updated_at_formatted,
            'status' => $selectedCategory->status,
        ] : null;
    @endphp
    @if ($showCategoryModal && $selectedCategoryArray)
        <livewire:search.categories-modal :selected-category="$selectedCategoryArray" :user-role="$userRole"
            :category-route="$categoryRoute" :key="'category-detail'" />
    @endif

    {{-- Branch Modal --}}
    @php
        $selectedBranchArray = $selectedBranch ? [
            'id' => $selectedBranch->id,
            'branche_name' => $selectedBranch->branche_name,
            'created_at_formatted' => $selectedBranch->created_at_formatted,
            'status' => $selectedBranch->status,
        ] : null;
    @endphp
    @if ($showBranchModal && $selectedBranchArray)
        <livewire:search.branches-modal :selected-branch="$selectedBranchArray" :user-role="$userRole"
            :branch-route="$branchRoute" :key="'branch-detail'" />
    @endif

    {{-- User Modal --}}
    @php
        $selectedUserArray = $selectedUser ? [
            'id' => $selectedUser->id,
            'username' => $selectedUser->username,
            'email' => $selectedUser->email,
            'role' => $selectedUser->role,
            'created_at_formatted' => $selectedUser->created_at_formatted,
            'status' => $selectedUser->status,
        ] : null;
    @endphp
    @if ($showUserModal && $selectedUserArray)
        <livewire:search.users-modal :selected-user="$selectedUserArray" :user-role="$userRole" :key="'user-detail'" />
    @endif

    {{-- Edit Product Modal --}}
    @if ($showEditProductModal && $selectedProduct)
        <livewire:search.edit-products-modal :selected-product="$selectedProduct" :key="'edit-prod-modal'" />
    @endif

    {{-- Edit Category Modal --}}
    @if ($showEditCategoryModal && $selectedCategory)
        <livewire:search.edit-categories-modal :selected-category="$selectedCategory" :key="'edit-cat-modal'" />
    @endif

    {{-- Edit Branch Modal --}}
    @if ($showEditBranchModal && $selectedBranch)
        <livewire:search.edit-branches-modal :selected-branch="$selectedBranch" :key="'edit-branch-modal'" />
    @endif

    {{-- Edit User Modal --}}
    @if ($showEditUserModal && $selectedUser)
        <livewire:search.edit-users-modal :selected-user="$selectedUser" :key="'edit-user-modal'" />
    @endif

    {{-- Erase Confirmation Modal --}}
    @if ($showConfirmErase)
        <livewire:search.erase-confirm-modal :erase-product-id="$eraseProductId" :erase-category-id="$eraseCategoryId"
            :erase-branch-id="$eraseBranchId" :erase-user-id="$eraseUserId" :key="'erase-confirm'" />
    @endif
</div>
