<?php

namespace App\Livewire\Products;

use App\Models\Products;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\On;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\Livewire;
use Livewire\WithPagination;

#[Layout('livewire.admin', params: ['current' => 'Produits'])]
#[Title('Produits')]
class Index extends Component
{
    use WithPagination;

    public ?string $statusFilter = null;

    public ?Products $selectedProduct = null;

    public bool $showDetailModal = false;

    public bool $showAddModal = false;

    public bool $showEditModal = false;

    public bool $showConfirmErase = false;

    public ?int $eraseProductId = null;

    public int $userRole;

    public string $sortField = 'updated_at';

    public string $sortDirection = 'desc';

    public int $tableRefreshKey = 0;

    public function mount(): void
    {
        $this->userRole = (int) Auth::user()->role;
    }


    public function setFilter(?string $filter): void
    {
        $this->statusFilter = $filter;
        $this->resetPage();
        $this->tableRefreshKey++;
    }

    public function sortBy(string $field): void
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }



    public function viewDetail(int $id): void
    {
        $this->selectedProduct = Products::with(['branch', 'category', 'deletedBy'])->findOrFail($id);
        $this->showDetailModal = true;
    }

    public function closeDetailModal(): void
    {
        $this->showDetailModal = false;
        $this->selectedProduct = null;
    }

    public function block(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $product = Products::findOrFail($id);
        $product->status = 2;
        $product->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Produit bloqué avec succès.');
    }

    public function unblock(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $product = Products::findOrFail($id);
        $product->status = 1;
        $product->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Produit débloqué avec succès.');
    }

    public function delete(int $id): void
    {
        $product = Products::findOrFail($id);

        if ($this->userRole === 1) {
            $product->status = 0;
        } elseif ($this->userRole === 2) {
            $product->status = 0;
        } else {
            return;
        }

        $product->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Produit supprimé avec succès.');
    }

    public function restore(int $id): void
    {
        if ($this->userRole > 2) {
            return;
        }

        $product = Products::findOrFail($id);
        $product->status = 1;
        $product->save();
        $this->closeDetailModal();
        $this->tableRefreshKey++;
        session()->flash('success', 'Produit restauré avec succès.');
    }

    public function confirmErase(int $id): void
    {
        $this->eraseProductId = $id;
        $this->showDetailModal = false;
        $this->showConfirmErase = true;
    }


    public function erase(): void
    {
        if ($this->eraseProductId) {
            if ($this->userRole === 1) {
                Products::findOrFail($this->eraseProductId)->forceDelete();
            } else {
                $product = Products::findOrFail($this->eraseProductId);
                $product->status = 3;
                $product->deleted_by = auth()->id();
                $product->save();
            }
            $this->closeDetailModal();
            $this->showConfirmErase = false;
            $this->eraseProductId = null;
            $this->tableRefreshKey++;
            session()->flash('success', 'Produit supprimé définitivement.');
        }
    }


    public function cancelErase(): void
    {
        $this->showConfirmErase = false;
        $this->eraseProductId = null;
    }
    /**
     * @param mixed $message
     */
    #[On('product-saved')]
    public function closeModals($message = ''): void
    {
        $this->showAddModal = false;
        $this->showEditModal = false;
        $this->selectedProduct = null;
        $this->tableRefreshKey++;

        if ($message) {
            session()->flash('success', $message);
        }
    }

    #[On('open-add-modal')]
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
        $this->selectedProduct = Products::with(['branch', 'category', 'deletedBy'])->findOrFail($id);
        $this->showDetailModal = false;
        $this->showEditModal = true;
    }
    /**
     * @param mixed $filter
     */
    #[On('tabs-set-filter')]
    public function handleTabsSetFilter($filter): void
    {
        $this->setFilter($filter);
    }
    /**
     * @param mixed $field
     */
    #[On('table-sort-by')]
    public function handleTableSortBy($field): void
    {
        $this->sortBy($field);
    }
    /**
     * @param mixed $id
     */
    #[On('table-view-detail')]
    public function handleTableViewDetail($id): void
    {
        $this->viewDetail($id);
    }

    public function render(): View
    {
        $query = Products::with(['branch', 'category', 'deletedBy'])->orderBy($this->sortField, $this->sortDirection);

        if ($this->userRole === 2) {
            $query->whereIn('status', [0, 1, 2]);
        } elseif ($this->userRole === 3) {
            $query->where('status', 1);
        }

        if ($this->statusFilter === 'active') {
            $query->where('status', 1);
        } elseif ($this->statusFilter === 'blocked') {
            $query->where('status', 2);
        } elseif ($this->statusFilter === 'deleted') {
            $query->where('status', 0);
        } elseif ($this->statusFilter === 'deleted_by_admin') {
            $query->where('status', 3);
        }

        $products = $query->paginate(10);
        $products->withPath(Livewire::originalUrl());

        return view('livewire.products.index', [
            'products' => $products,
        ]);
    }
}
