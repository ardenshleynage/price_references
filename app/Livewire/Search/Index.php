<?php

namespace App\Livewire\Search;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('livewire.admin', params: ['current' => 'Résultats'])]
#[Title('Recherche')]
class Index extends Component
{
    #[Url(as: 'q')]
    public string $query = '';

    public int $userRole;

    public array $results = [];

    public bool $showProductModal = false;

    public ?Products $selectedProduct = null;

    public bool $showBranchModal = false;

    public ?Branches $selectedBranch = null;

    public bool $showCategoryModal = false;

    public ?Categories $selectedCategory = null;

    public bool $showUserModal = false;

    public ?User $selectedUser = null;

    public bool $showEditProductModal = false;

    public bool $showEditCategoryModal = false;

    public bool $showEditBranchModal = false;

    public bool $showEditUserModal = false;

    public bool $showConfirmErase = false;

    public ?int $eraseProductId = null;

    public ?int $eraseCategoryId = null;

    public ?int $eraseBranchId = null;

    public ?int $eraseUserId = null;

    public function mount(): void
    {
        $this->userRole = (int) Auth::user()->role;
        $this->search();
    }

    public function updatedQuery(): void
    {
        $this->search();
    }

    protected function search(): void
    {
        $q = trim($this->query);

        if ($q === '') {
            $this->results = [];

            return;
        }

        $like = "%{$q}%";
        $role = $this->userRole;

        $statusFilter = match ($role) {
            1 => null,
            2 => [0, 1, 2],
            3 => [1],
            default => [1],
        };

        $categoryStatusFilter = match ($role) {
            1 => null,
            2 => [0, 1, 2],
            3 => [1],
            default => [1],
        };

        $this->results = [];

        if ($role === 1 || $role === 2 || $role === 3) {
            $products = Products::with(['branch', 'category'])
                ->when($statusFilter, fn($qry) => $qry->whereIn('status', $statusFilter))
                ->where(function ($qry) use ($like) {
                    $qry->where('product_name', 'like', $like)
                        ->orWhere('post_scriptum', 'like', $like);
                })
                ->orderBy('product_name')
                ->get();

            $this->results['products'] = $products;

            $categories = Categories::where(function ($qry) use ($like) {
                $qry->where('category_name', 'like', $like);
            })
                ->when($categoryStatusFilter, fn($qry) => $qry->whereIn('status', $categoryStatusFilter))
                ->orderBy('category_name')
                ->get();

            $this->results['categories'] = $categories;

            $branches = Branches::where(function ($qry) use ($like) {
                $qry->where('branche_name', 'like', $like);
            })
                ->when($statusFilter, fn($qry) => $qry->whereIn('status', $statusFilter))
                ->orderBy('branche_name')
                ->get();

            $this->results['branches'] = $branches;
        }

        if ($role === 1) {
            $users = User::where('username', 'like', $like)
                ->where('role', '!=', 1)
                ->orderBy('username')
                ->get();

            $this->results['users'] = $users;
        }
    }

    #[On('search-show-product')]
    public function handleShowProduct(int $id): void
    {
        $this->showProduct($id);
    }

    #[On('search-close-product-modal')]
    public function handleCloseProductModal(): void
    {
        $this->closeProductModal();
    }

    public function showProduct(int $id): void
    {
        $this->selectedProduct = Products::with(['branch', 'category'])->findOrFail($id);
        $this->showProductModal = true;
    }

    public function closeProductModal(): void
    {
        $this->showProductModal = false;
        $this->selectedProduct = null;
    }

    #[On('search-show-branch')]
    public function handleShowBranch(int $id): void
    {
        $this->showBranch($id);
    }

    #[On('search-close-branch-modal')]
    public function handleCloseBranchModal(): void
    {
        $this->closeBranchModal();
    }

    public function showBranch(int $id): void
    {
        $this->selectedBranch = Branches::findOrFail($id);
        $this->showBranchModal = true;
    }

    public function closeBranchModal(): void
    {
        $this->showBranchModal = false;
        $this->selectedBranch = null;
    }

    #[On('search-show-category')]
    public function handleShowCategory(int $id): void
    {
        $this->showCategory($id);
    }

    #[On('search-close-category-modal')]
    public function handleCloseCategoryModal(): void
    {
        $this->closeCategoryModal();
    }

    public function showCategory(int $id): void
    {
        $this->selectedCategory = Categories::findOrFail($id);
        $this->showCategoryModal = true;
    }

    public function closeCategoryModal(): void
    {
        $this->showCategoryModal = false;
        $this->selectedCategory = null;
    }

    #[On('search-show-user')]
    public function handleShowUser(int $id): void
    {
        $this->showUser($id);
    }

    #[On('search-close-user-modal')]
    public function handleCloseUserModal(): void
    {
        $this->closeUserModal();
    }

    public function showUser(int $id): void
    {
        $this->selectedUser = User::findOrFail($id);
        $this->showUserModal = true;
    }

    public function closeUserModal(): void
    {
        $this->showUserModal = false;
        $this->selectedUser = null;
    }

    #[On('search-block-product')]
    public function handleBlockProduct(int $id): void
    {
        $this->blockProduct($id);
    }

    public function blockProduct(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 2;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit bloqué avec succès.');
    }

    #[On('search-unblock-product')]
    public function handleUnblockProduct(int $id): void
    {
        $this->unblockProduct($id);
    }

    public function unblockProduct(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 1;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit débloqué avec succès.');
    }

    #[On('search-delete-product')]
    public function handleDeleteProduct(int $id): void
    {
        $this->deleteProduct($id);
    }

    public function deleteProduct(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 0;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit supprimé avec succès.');
    }

    #[On('search-restore-product')]
    public function handleRestoreProduct(int $id): void
    {
        $this->restoreProduct($id);
    }

    public function restoreProduct(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 1;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit restauré avec succès.');
    }

    #[On('search-confirm-erase-product')]
    public function handleConfirmEraseProduct(int $id): void
    {
        $this->confirmEraseProduct($id);
    }

    public function confirmEraseProduct(int $id): void
    {
        $this->eraseProductId = $id;
        $this->showConfirmErase = true;
    }

    #[On('search-block-category')]
    public function handleBlockCategory(int $id): void
    {
        $this->blockCategory($id);
    }

    public function blockCategory(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 2;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie bloquée avec succès.');
    }

    #[On('search-unblock-category')]
    public function handleUnblockCategory(int $id): void
    {
        $this->unblockCategory($id);
    }

    public function unblockCategory(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie débloquée avec succès.');
    }

    #[On('search-delete-category')]
    public function handleDeleteCategory(int $id): void
    {
        $this->deleteCategory($id);
    }

    public function deleteCategory(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 0;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie supprimée avec succès.');
    }

    #[On('search-restore-category')]
    public function handleRestoreCategory(int $id): void
    {
        $this->restoreCategory($id);
    }

    public function restoreCategory(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie restaurée avec succès.');
    }

    #[On('search-confirm-erase-category')]
    public function handleConfirmEraseCategory(int $id): void
    {
        $this->confirmEraseCategory($id);
    }

    public function confirmEraseCategory(int $id): void
    {
        $this->eraseCategoryId = $id;
        $this->showConfirmErase = true;
    }

    #[On('search-block-branch')]
    public function handleBlockBranch(int $id): void
    {
        $this->blockBranch($id);
    }

    public function blockBranch(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 2;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche bloquée avec succès.');
    }

    #[On('search-unblock-branch')]
    public function handleUnblockBranch(int $id): void
    {
        $this->unblockBranch($id);
    }

    public function unblockBranch(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche débloquée avec succès.');
    }

    #[On('search-delete-branch')]
    public function handleDeleteBranch(int $id): void
    {
        $this->deleteBranch($id);
    }

    public function deleteBranch(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 0;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche supprimée avec succès.');
    }

    #[On('search-restore-branch')]
    public function handleRestoreBranch(int $id): void
    {
        $this->restoreBranch($id);
    }

    public function restoreBranch(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche restaurée avec succès.');
    }

    #[On('search-confirm-erase-branch')]
    public function handleConfirmEraseBranch(int $id): void
    {
        $this->confirmEraseBranch($id);
    }

    public function confirmEraseBranch(int $id): void
    {
        $this->eraseBranchId = $id;
        $this->showConfirmErase = true;
    }

    #[On('search-block-user')]
    public function handleBlockUser(int $id): void
    {
        $this->blockUser($id);
    }

    public function blockUser(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $user = User::findOrFail($id);
        $user->status = 2;
        $user->save();
        $this->closeUserModal();
        $this->search();
        session()->flash('success', 'Utilisateur bloqué avec succès.');
    }

    #[On('search-unblock-user')]
    public function handleUnblockUser(int $id): void
    {
        $this->unblockUser($id);
    }

    public function unblockUser(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();
        $this->closeUserModal();
        $this->search();
        session()->flash('success', 'Utilisateur débloqué avec succès.');
    }

    #[On('search-delete-user')]
    public function handleDeleteUser(int $id): void
    {
        $this->deleteUser($id);
    }

    public function deleteUser(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $user = User::findOrFail($id);
        $user->status = 0;
        $user->save();
        $this->closeUserModal();
        $this->search();
        session()->flash('success', 'Utilisateur supprimé avec succès.');
    }

    #[On('search-restore-user')]
    public function handleRestoreUser(int $id): void
    {
        $this->restoreUser($id);
    }

    public function restoreUser(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $user = User::findOrFail($id);
        $user->status = 1;
        $user->save();
        $this->closeUserModal();
        $this->search();
        session()->flash('success', 'Utilisateur restauré avec succès.');
    }

    #[On('search-confirm-erase-user')]
    public function handleConfirmEraseUser(int $id): void
    {
        $this->confirmEraseUser($id);
    }

    public function confirmEraseUser(int $id): void
    {
        $this->eraseUserId = $id;
        $this->showConfirmErase = true;
    }

    #[On('search-open-edit-product-modal')]
    public function handleOpenEditProductModal(int $id): void
    {
        $this->openEditProductModal($id);
    }

    #[On('search-close-edit-product-modal')]
    public function handleCloseEditProductModal(): void
    {
        $this->showEditProductModal = false;
        $this->selectedProduct = null;
        $this->search();
    }

    public function openEditProductModal(int $id): void
    {
        $this->selectedProduct = Products::with(['branch', 'category'])->findOrFail($id);
        $this->showEditProductModal = true;
    }
    /**
     * @param mixed $message
     */
    #[On('product-saved')]
    public function closeEditProductModal($message = ''): void
    {
        $this->showEditProductModal = false;
        $this->selectedProduct = null;
        $this->search();
        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[On('search-open-edit-category-modal')]
    public function handleOpenEditCategoryModal(int $id): void
    {
        $this->openEditCategoryModal($id);
    }

    #[On('search-close-edit-category-modal')]
    public function handleCloseEditCategoryModal(): void
    {
        $this->showEditCategoryModal = false;
        $this->selectedCategory = null;
        $this->search();
    }

    public function openEditCategoryModal(int $id): void
    {
        $this->selectedCategory = Categories::findOrFail($id);
        $this->showEditCategoryModal = true;
    }
    /**
     * @param mixed $message
     */
    #[On('category-saved')]
    public function closeEditCategoryModal($message = ''): void
    {
        $this->showEditCategoryModal = false;
        $this->selectedCategory = null;
        $this->search();
        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[On('search-open-edit-branch-modal')]
    public function handleOpenEditBranchModal(int $id): void
    {
        $this->openEditBranchModal($id);
    }

    #[On('search-close-edit-branch-modal')]
    public function handleCloseEditBranchModal(): void
    {
        $this->showEditBranchModal = false;
        $this->selectedBranch = null;
        $this->search();
    }

    public function openEditBranchModal(int $id): void
    {
        $this->selectedBranch = Branches::findOrFail($id);
        $this->showEditBranchModal = true;
    }
    /**
     * @param mixed $message
     */
    #[On('branch-saved')]
    public function closeEditBranchModal($message = ''): void
    {
        $this->showEditBranchModal = false;
        $this->selectedBranch = null;
        $this->search();
        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[On('search-cancel-erase')]
    public function handleCancelErase(): void
    {
        $this->cancelErase();
    }

    #[On('search-erase')]
    public function handleErase(): void
    {
        $this->erase();
    }

    #[On('search-open-edit-user-modal')]
    public function handleOpenEditUserModal(int $id): void
    {
        $this->openEditUserModal($id);
    }

    #[On('search-close-edit-user-modal')]
    public function handleCloseEditUserModal(): void
    {
        $this->showEditUserModal = false;
        $this->selectedUser = null;
        $this->search();
    }

    public function openEditUserModal(int $id): void
    {
        $this->selectedUser = User::findOrFail($id);
        $this->showEditUserModal = true;
    }
    /**
     * @param mixed $message
     */
    #[On('user-saved')]
    public function closeEditUserModal($message = ''): void
    {
        $this->showEditUserModal = false;
        $this->selectedUser = null;
        $this->search();
        if ($message) {
            session()->flash('success', $message);
        }
    }

    public function erase(): void
    {
        if ($this->eraseProductId) {
            $product = Products::findOrFail($this->eraseProductId);
            if ($this->userRole === 1) {
                $product->forceDelete();
            } else {
                $product->status = 3;
                $product->save();
            }
            $this->eraseProductId = null;
            $this->closeProductModal();
            $this->search();
            session()->flash('success', 'Produit supprimé définitivement.');
        } elseif ($this->eraseCategoryId) {
            $category = Categories::findOrFail($this->eraseCategoryId);
            if ($this->userRole === 1) {
                $category->forceDelete();
            } else {
                $category->status = 3;
                $category->save();
            }
            $this->eraseCategoryId = null;
            $this->closeCategoryModal();
            $this->search();
            session()->flash('success', 'Catégorie supprimée définitivement.');
        } elseif ($this->eraseBranchId) {
            $branch = Branches::findOrFail($this->eraseBranchId);
            if ($this->userRole === 1) {
                $branch->forceDelete();
            } else {
                $branch->status = 3;
                $branch->save();
            }
            $this->eraseBranchId = null;
            $this->closeBranchModal();
            $this->search();
            session()->flash('success', 'Branche supprimée définitivement.');
        } elseif ($this->eraseUserId) {
            $user = User::findOrFail($this->eraseUserId);
            if ($this->userRole === 1) {
                $user->forceDelete();
            } else {
                $user->status = 3;
                $user->save();
            }
            $this->eraseUserId = null;
            $this->closeUserModal();
            $this->search();
            session()->flash('success', 'Utilisateur supprimé définitivement.');
        }
        $this->showConfirmErase = false;
    }

    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseProductId = null;
        $this->eraseCategoryId = null;
        $this->eraseBranchId = null;
        $this->eraseUserId = null;
    }

    public function render(): View
    {
        $productRoute = match ($this->userRole) {
            1 => 'super_admin_products',
            2 => 'admins_products',
            3 => 'readers_products',
            default => 'login',
        };

        $categoryRoute = match ($this->userRole) {
            1 => 'super_admin_categories',
            2 => 'admins_categories',
            3 => 'readers_categories',
            default => 'login',
        };

        $branchRoute = match ($this->userRole) {
            1 => 'super_admin_branches',
            2 => 'admins_branches',
            3 => 'readers_branches',
            default => 'login',
        };

        return view('livewire.search.index', compact(
            'productRoute',
            'categoryRoute',
            'branchRoute'
        ));
    }
}
