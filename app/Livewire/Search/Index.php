<?php

namespace App\Livewire\Search;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
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
            2 => [1, 2],
            3 => [1],
            default => [1],
        };

        $this->results = [];

        if ($role === 1 || $role === 2 || $role === 3) {
            $products = Products::with(['branch', 'category'])
                ->when($statusFilter, fn ($qry) => $qry->whereIn('status', $statusFilter))
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
                ->when($statusFilter, fn ($qry) => $qry->whereIn('status', $statusFilter))
                ->orderBy('category_name')
                ->get();

            $this->results['categories'] = $categories;

            $branches = Branches::where(function ($qry) use ($like) {
                $qry->where('branche_name', 'like', $like);
            })
                ->when($statusFilter, fn ($qry) => $qry->whereIn('status', $statusFilter))
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

    public function blockProduct(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 2;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit bloqué avec succès.');
    }

    public function unblockProduct(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $product = Products::findOrFail($id);
        $product->status = 1;
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit débloqué avec succès.');
    }

    public function deleteProduct(int $id): void
    {
        $product = Products::findOrFail($id);
        if ($this->userRole === 1) {
            $product->status = 0;
        } elseif ($this->userRole === 2) {
            $product->status = 2;
        } else {
            return;
        }
        $product->save();
        $this->closeProductModal();
        $this->search();
        session()->flash('success', 'Produit supprimé avec succès.');
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

    public function confirmEraseProduct(int $id): void
    {
        $this->eraseProductId = $id;
        $this->showConfirmErase = true;
    }

    public function blockCategory(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 2;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie bloquée avec succès.');
    }

    public function unblockCategory(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $category = Categories::findOrFail($id);
        $category->status = 1;
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie débloquée avec succès.');
    }

    public function deleteCategory(int $id): void
    {
        $category = Categories::findOrFail($id);
        if ($this->userRole === 1) {
            $category->status = 0;
        } elseif ($this->userRole === 2) {
            $category->status = 2;
        } else {
            return;
        }
        $category->save();
        $this->closeCategoryModal();
        $this->search();
        session()->flash('success', 'Catégorie supprimée avec succès.');
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

    public function confirmEraseCategory(int $id): void
    {
        $this->eraseCategoryId = $id;
        $this->showConfirmErase = true;
    }

    public function blockBranch(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 2;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche bloquée avec succès.');
    }

    public function unblockBranch(int $id): void
    {
        if ($this->userRole !== 1) {
            return;
        }
        $branch = Branches::findOrFail($id);
        $branch->status = 1;
        $branch->save();
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche débloquée avec succès.');
    }

    public function deleteBranch(int $id): void
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
        $this->closeBranchModal();
        $this->search();
        session()->flash('success', 'Branche supprimée avec succès.');
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

    public function confirmEraseBranch(int $id): void
    {
        $this->eraseBranchId = $id;
        $this->showConfirmErase = true;
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

    public function confirmEraseUser(int $id): void
    {
        $this->eraseUserId = $id;
        $this->showConfirmErase = true;
    }

    public function erase(): void
    {
        if ($this->eraseProductId) {
            $product = Products::findOrFail($this->eraseProductId);
            if ($this->userRole === 1) {
                $product->forceDelete();
            } else {
                $product->status = 0;
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
                $category->status = 0;
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
                $branch->status = 0;
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
                $user->status = 0;
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

    public function render()
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
            'productRoute', 'categoryRoute', 'branchRoute'
        ));
    }
}
