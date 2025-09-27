<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Toast extends Component
{
    public $type;
    public $message;
    public $id;
    /**
     * Create a new component instance.
     *
     * @param string $type
     * @param string $message
     * @param string $id
     */
    public function __construct($type = 'success', $message = '', $id = '')
    {
        $this->type = $type;
        $this->message = $message;
        $this->id = $id ?: 'toast-' . uniqid(); // Unique ID for potential styling
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render(): View|Closure|string
    {
        return view('components.toast');
    }
}
