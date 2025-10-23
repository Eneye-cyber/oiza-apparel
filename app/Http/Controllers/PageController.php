<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Products\Product;
use App\Models\Faq;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Support\Str;

class PageController extends Controller
{
    //


    public function home(): View
    {
        $product = null;
        $featured = null;
        $faq = null;

        try {
            //code...
            $products = Product::where('is_active', true)->latest()->take(8)->get();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error fetching latest products for home page', [
                'controller' => 'PageController',
                'method' => 'home',
                'error' => $th->getMessage(),
            ]);
            $products = collect(); // Fallback to empty collection if error occurs
        }

        try {
            //code...
            $featured = Product::where('is_featured', true)->latest()->take(9)->get();
        } catch (\Throwable $th) {
            //throw $th;
            Log::error('Error fetching featured products for home page', [
                'controller' => 'PageController',
                'method' => 'home',
                'error' => $th->getMessage(),
            ]);
            $featured = collect(); // Fallback to empty collection if error occurs
        }

        try {
            $faq = Faq::where('is_active', true)->get();
        } catch (\Throwable $th) {
            Log::error('Error fetching FAQs for home page', [
                'controller' => 'PageController',
                'method' => 'home',
                'error' => $th->getMessage(),
            ]);
            $faq = collect();
        }
        return view('pages.home', compact('products', 'featured', 'faq'));
    }

    public function shop(Request $request, CategoryService $categoryService): View
    {
        try {
            $query = null;
            // Initialize query for active products
            if ($request->has('category') || $request->has('subcategory')) {
                $slug = $request->input('subcategory') ?? $request->input('category');
                Log::info('categorySlug', ['categorySlug' => $slug]);
                $category = Category::where('slug', $slug)->first();
                Log::info('category', ['category' => $category]);
                if (!$category) {
                    Log::warning('Category not found for shop page', [
                        'controller' => 'PageController',
                        'method' => 'shop',
                        'slug' => $slug,
                    ]);
                    abort(404, 'Category not found');
                }
                $query = $category->getAllProductsQuery()
                    ->where('is_active', true);
            } else {
                $query = Product::where('is_active', true);
            }

            // Filter by color
            // if ($request->has('color') && $request->input('color') !== '') {
            //     $query->where('color', $request->input('color'));
            // }

            // Filter by price range
            if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
                $query->where('price', '>=', $request->input('min_price'));
            }
            if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
                $query->where('price', '<=', $request->input('max_price'));
            }

            // Sort products
            $sort = $request->input('sort', '');
            switch ($sort) {
                case 'price-asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price-desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'name-asc':
                    $query->orderBy('name', 'asc');
                    break;
                case 'name-desc':
                    $query->orderBy('name', 'desc');
                    break;
                case 'oldest':
                    $query->oldest();
                    break;
                default:
                    $query->latest();
                    break;
            }

            // Paginate results
            $products = $query->paginate(40)->appends($request->query());
            Log::info('Paginated products for shop page', [
                'controller' => 'PageController',
                'method' => 'shop',
                'total_products' => $products->total(),
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
            ]);

            // Fetch categories and subcategories
            $categories = $categoryService->getCategoriesWithChildren();

            return view('pages.shop.index', compact('products', 'categories'));
        } catch (\Throwable $th) {
            Log::error('Error fetching products for shop page', [
                'controller' => 'PageController',
                'method' => 'shop',
                'error' => $th->getMessage(),
            ]);
            $products = collect(); // Fallback to empty collection
            $categories = $categoryService->getCategoriesWithChildren();
            return view('pages.shop.index', compact('products', 'categories'));
        }
    }

    public function product(Product $product, CategoryService $categoryService): View
    {
        try {
            // Ensure the product is active
            if (!$product->is_active) {
                Log::warning('Attempted to access inactive product', [
                    'controller' => 'PageController',
                    'method' => 'product',
                    'product_id' => $product->id,
                ]);
                abort(404, 'Product not found');
            }

            // Eager load the variants and their attributes
            $product->load('variants.attributes');
            Log::info('Loaded product with variants and attributes', [
                'controller' => 'PageController',
                'method' => 'product',
                'product' => $product,
                'product_id' => $product->id,
                'variants_count' => $product->variants->count(),
            ]);


            // Fetch the category for breadcrumbs and context
            $category = $product->category;
            if (!$category) {
                Log::warning('Product has no associated category', [
                    'controller' => 'PageController',
                    'method' => 'product',
                    'product_id' => $product->id,
                ]);
                abort(404, 'Product category not found');
            }
            $breadcrumbs = $categoryService->getBreadcrumbs($category->full_slug_path);

            $tags_string = implode(', ', $product['tags']);

            $similarProducts = $product->getRelatedProducts(4);

            return view('pages.shop.product', compact('product', 'category', 'tags_string', 'breadcrumbs', 'similarProducts'));
        } catch (\Throwable $th) {
            Log::error('Error fetching product details', [
                'controller' => 'PageController',
                'method' => 'product',
                'product_id' => $product->id ?? null,
                'error' => $th->getMessage(),
            ]);
            abort(404, 'Product not found');
        }
    }

    public function show(Request $request, $slug, CategoryService $categoryService)
    {
        Log::info('Show method called with parameters', [
            'controller' => 'PageController',
            'method' => 'show',
            'category' => $slug,
        ]);
        $segments = explode('/', $slug);
        $count = count($segments);
        // get the parent category if exists
        $parentCategorySlug = $count > 1 ? $segments[$count - 2] : null;
        // get the page main category for product listing
        $categorySlug = $segments[$count - 1];

        $category = Category::where('slug', $categorySlug)->first();
        if (!$category) {
            Log::warning('Category not found for shop page', [
                'controller' => 'PageController',
                'method' => 'shop',
                'slug' => $categorySlug,
            ]);
            abort(404, 'Category not found');
        }

        // Get the siblings if parent category exists else get children of the current category
        $parent = $parentCategorySlug ? Category::where('slug', $parentCategorySlug)
            ->first() : null;
        Log::info('Parent category', ['parent' => $parent]);

        $breadcrumbs = $categoryService->getBreadcrumbs($slug);
        $sidebarCategories = [];

        if ($parent) {
            // Parent exists: Fetch parent details and its children (including current child)
            $sidebarCategories['parent'] = $parent;
            $sidebarCategories['children'] = $parent->children()
                ->select('name', 'slug', 'full_slug_path')
                ->get();
            Log::info('Sidebar categories with parent', ['sidebarCategories' => $sidebarCategories]);
        } else {
            // No parent: Fetch children of the current category
            $currentCategory = $category;
            $sidebarCategories['parent'] = $currentCategory;
            $sidebarCategories['children'] = $currentCategory
                ? $currentCategory->children()
                ->select('name', 'slug', 'full_slug_path')
                ->get()
                : collect();
            Log::info('Sidebar categories without parent', ['sidebarCategories' => $sidebarCategories]);
        }


        $query = $category->getAllProductsQuery()
            ->where('is_active', true);


        // Filter by color
        // if ($request->has('color') && $request->input('color') !== '') {
        //     $query->where('color', $request->input('color'));
        // }

        // Filter by price range
        if ($request->has('min_price') && is_numeric($request->input('min_price'))) {
            $query->where('price', '>=', $request->input('min_price'));
        }
        if ($request->has('max_price') && is_numeric($request->input('max_price'))) {
            $query->where('price', '<=', $request->input('max_price'));
        }

        // Sort products
        $sort = $request->input('sort', '');
        switch ($sort) {
            case 'price-asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price-desc':
                $query->orderBy('price', 'desc');
                break;
            case 'name-asc':
                $query->orderBy('name', 'asc');
                break;
            case 'name-desc':
                $query->orderBy('name', 'desc');
                break;
            case 'oldest':
                $query->oldest();
                break;

            default:
                $query->latest();
                break;
        }

        // Return shop index view
        $products = $query->paginate(40)->appends($request->query());
        Log::info('Paginated products for category page', [
            'controller' => 'PageController',
            'method' => 'show',
            'category' => $categorySlug,
            'total_products' => $products->total(),
            'current_page' => $products->currentPage(),
            'per_page' => $products->perPage(),
        ]);
        $categoryModel = collect(['name' => 'Fabrics', 'slug' => 'fabrics']);
        $meta = [
            'title' => $category->meta_title ?? $category->name . ' Products',
            'description' => $category->meta_description ?? $category->description ?? 'Browse our selection of ' . Str::lower($category->name) . ' products.',
            'keywords' => $category->meta_keywords ? implode(', ', $category->meta_keywords) : implode(', ', $segments) . ', products, shop',
        ];
        return view('pages.shop.category', compact('products', 'category', 'sidebarCategories', 'meta', 'breadcrumbs'));
    }

    public function order(Request $request)
    {
         $ref = $request->query('ref'); // or $request->get('ref')
        Log::info("order uuid: " . $ref);

        return view('pages.order');
    }

    public function privacy()
    {
        abort(404); // Returns a 404 Not Found response
    }

    public function terms()
    {
        abort(404); // Returns a 404 Not Found response
    }

    public function sitemap()
    {
        abort(404); // Returns a 404 Not Found response
    }
}
