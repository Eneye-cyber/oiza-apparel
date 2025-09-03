<?php

namespace App\Services;

use App\Models\Category;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Dedicated CategoryService class that handles fetching and formatting the category hierarchy
 *
 */

class CategoryService
{

    /**
     * Recursively map subcategories to maintain hierarchy.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $children
     * @return array
     */
    protected function mapSubcategories($children): array
    {
        return $children->map(function ($child) {
            return [
                'id' => $child->id,
                'name' => $child->name,
                'slug' => $child->full_slug_path,
                'subcategories' => $this->mapSubcategories($child->children),
            ];
        })->toArray();
    }

    /**
     * Fetch and cache the category hierarchy for the header.
     * Use dependency injection to provide the CategoryService to other parts of the app
     *
     * @return array
     */
    public function getCategoriesWithChildren(): array
    {
        // Keep the caching mechanism to avoid repeated database queries, but manage it within the service.
        return Cache::remember('header_categories', now()->addHours(24), function () {
            $categories = Category::whereNull('parent_id')
                ->with(['children' => function ($query) {
                    $query->select('id', 'name', 'full_slug_path', 'parent_id')
                        ->with(['children' => function ($subQuery) {
                            $subQuery->select('id', 'name', 'parent_id', 'full_slug_path')
                                ->with('children:id,name,parent_id,full_slug_path');
                        }]);
                }])
                ->select('id', 'name', 'slug')
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->name,
                        'slug' => $category->slug,
                        'subcategories' => $this->mapSubcategories($category->children),
                    ];
                })->toArray();

            Log::info('Header categories fetched and cached', [
                'categories' => $categories,
            ]);

            return $categories;
        });
    }


    /**
     * Get categories filtered by top-level names.
     *
     * @param  array  $topLevelNames
     * @return array
     */
    public function getFilteredCategories(array $topLevelNames): array
    {
        $categories = $this->getCategoriesWithChildren();
        return array_filter($categories, fn($category) => in_array($category['name'], $topLevelNames));
    }

    /**
     * Get breadcrumbs from full_path_slugs.
     *
     * @param  string  $full_path_slugs
     * @return array<int, array{id: int, name: string, slug: string}>
     */
    public function getBreadcrumbs(string $fullPath): array
    {
        $segments = explode('/', $fullPath); // e.g. "men/shoes/sneakers" → ['men','shoes','sneakers']
        $prev_segment = '';

        $categories = $this->getCategoriesWithChildren();
        $breadcrumbs = [];

        foreach ($segments as $segment) {
            $query = $prev_segment. '' .$segment;
            $found = collect($categories)->firstWhere('slug', $query);
 
            if ($found) {
                $breadcrumbs[] = [
                    'id'   => $found['id'],
                    'name' => $found['name'],
                    'slug' => $found['slug'],
                ];

                // move deeper into the tree for next iteration
                $categories = $found['subcategories'];
                $prev_segment = $query.'/';
            } else {
                break; // invalid path segment → stop
            }
        }

        return $breadcrumbs;
    }
}
