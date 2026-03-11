<?php

namespace App\View\Components\admins\search;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class ConfirmEraseModal extends Component
{
    public $message;

    public $confirmText;

    public $cancelText;

    public $id;

    public $confirmBtnId;

    /**
     * Create a new component instance.
     * @param mixed $message
     * @param mixed $confirmText
     * @param mixed $cancelText
     * @param mixed $id
     * @param mixed $confirmBtnId
     */
    public function __construct(
        $message = 'Êtes-vous sûr de vouloir supprimer définitivement cet élément ?',
        $confirmText = 'Oui',
        $cancelText = 'Non',
        $id = 'confirmEraseModal',
        $confirmBtnId = 'confirmEraseBtn'
    ) {
        $this->message = $message;
        $this->confirmText = $confirmText;
        $this->cancelText = $cancelText;
        $this->id = $id;
        $this->confirmBtnId = $confirmBtnId;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.admins.search.confirm-erase-modal');
    }
}
