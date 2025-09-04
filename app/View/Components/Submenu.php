<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Submenu extends Component
{

    public $subcategories;
    public $parentSlug;
    /**
     * Create a new component instance.
     */
    public function __construct($subcategories, $parentSlug = '')
    {
        $this->subcategories = $subcategories;
        $this->parentSlug = $parentSlug;
    }


    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.submenu');
    }
}
