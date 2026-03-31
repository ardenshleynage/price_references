<?php

namespace App\View\Components\mobile\search\branches;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class BranchDetailModal extends Component
{
    public function __construct()
    {
        //
    }

    public function render(): View|Closure|string
    {
        return view('components.mobile.search.branches.branch-detail-modal');
    }
}
