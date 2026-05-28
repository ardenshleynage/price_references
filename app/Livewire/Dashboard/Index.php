<?php

namespace App\Livewire\Dashboard;

use App\Models\Branches;
use App\Models\Categories;
use App\Models\Products;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('livewire.admin', params: ['current' => 'Accueil'])]
#[Title('Dashboard')]
class Index extends Component
{
    public int $totalProducts;

    public int $totalUsers;

    public int $totalCategories;

    public int $totalBranches;

    public string $rolePrefix = '';

    public function mount(): void
    {
        $user = Auth::user();

        $this->rolePrefix = match ((int) $user->role) {
            1 => 'super_admin',
            2 => 'admins',
            3 => 'readers',
        };

        match ((int) $user->role) {
            1 => $this->loadSuperAdminStats(),
            2 => $this->loadAdminStats(),
            3 => $this->loadReaderStats(),
        };
    }

    private function loadSuperAdminStats(): void
    {
        $this->totalProducts = Products::count();
        $this->totalUsers = User::where('role', '!=', 1)->count();
        $this->totalCategories = Categories::count();
        $this->totalBranches = Branches::count();
    }

    private function loadAdminStats(): void
    {
        $this->totalProducts = Products::whereIn('status', [1, 2])->count();
        $this->totalCategories = Categories::whereIn('status', [1, 2])->count();
        $this->totalBranches = Branches::whereIn('status', [1, 2])->count();
        $this->totalUsers = 0;
    }

    private function loadReaderStats(): void
    {
        $this->totalProducts = Products::where('status', 1)->count();
        $this->totalCategories = Categories::where('status', 1)->count();
        $this->totalBranches = Branches::where('status', 1)->count();
        $this->totalUsers = 0;
    }

    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
