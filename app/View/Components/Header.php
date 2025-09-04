<?php

namespace App\View\Components;

use App\Services\CategoryService;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Header extends Component
{
    public $categories;

    /**
     * Create a new component instance.
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categories = $categoryService->getCategoriesWithChildren();
        // This will fetch the categories with children using the CategoryService.
        // The service handles caching and formatting, keeping the component clean.;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|string
    {
        return view('components.header');
    }
}