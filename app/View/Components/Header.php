<?php

namespace App\View\Components;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\Component;

class Header extends Component
{
    public $categories;
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
        // You can cache this if categories don’t change often
        // Cache::flush();
        $this->categories = Cache::remember('header_categories', now()->addHours(24), function () {
            return Category::whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query->select('id', 'name', 'full_slug_path', 'parent_id')
                        ->with(['children' => function ($subQuery) {
                            // Recursively load all nested children
                            $subQuery->select('id', 'name', 'parent_id', 'full_slug_path')
                                ->with('children:id,name,parent_id,full_slug_path');
                        }]);
                }])
                ->select('id', 'name', 'slug')
                ->get()
                ->map(function ($category) {
                    return [
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'subcategories' => $this->mapSubcategories($category->children),
                    ];
                })->toArray();
        });
        Log::info('Header component initialized with categories', [
            'categories' => $this->categories,
        ]);
    }

     /**
     * Recursively map subcategories to maintain hierarchy.
     */
    private function mapSubcategories($children): array
    {
        return $children->map(function ($child) {
            return [
                'name' => $child->name,
                // 'slug' => $child->slug,
                'slug' => $child->full_slug_path,
                'subcategories' => $this->mapSubcategories($child->children),
            ];
        })->toArray();
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.header');
    }
}
