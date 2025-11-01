@extends('layouts.main')

@section('title', 'Shop')

@section('modal')
  <x-modals.quick-view />
@endsection

@section('content')
    <article class="pb-12">
        <div class="bg-gold py-5">
            <div class="container">
                <dl class="flex gap-2.5">
                    <a href="/" class="breadcrumb-link breadcrumb-link-divider">Home</a>
                    <a href="/shop" class="breadcrumb-link breadcrumb-link-active">Products</a>
                </dl>
            </div>
        </div>
        <div class="container">
            <div class="py-8 sm:mb-0 sm:py-12 text-center">
                <h2 class="page-title">Shop</h2>
            </div>

            {{-- <div class="separator mb-3 sm:hidden"></div> --}}

            <section class="flex flex-col flex-between">
                <div class="flex items-start flex-wrap w-full">
                    <div class="md:hidden relative w-full py-1 mb-4 border-y border-black/30" aria-haspopup="true"
                        aria-expanded="false">
                        <button id="sidebar-trigger"
                            class="py-3 w-full text-left flex items-center justify-between hover:text-[#555] text-primary duration-200"
                            aria-label="Categories menu" data-submenu-toggle="">
                            <span class="capitalize">{{ request()->get('subcategory') ?? request()->get('category') ?? 'All products'}}</span>
                            <x-heroicon-c-arrow-right class="h-3.5 w-auto" />
                        </button>
                        <ul class="pl-4 space-y-2 hidden">

                        </ul>
                    </div>
                    <aside id="shop-sidebar" class="sidebar">
                        {{-- All products --}}
                        <a href="/shop"
                            class="sidebar-link sidebar-link-hover {{ !request()->has('category') ? 'sidebar-link-active' : '' }}">
                            All products
                        </a>

                        {{-- Categories --}}
                        @foreach ($categories as $category)
                            <div class="flex flex-col w-full">
                                <a href="/shop?category={{ Str::slug($category['name']) }}"
                                    class="sidebar-link sidebar-link-hover {{ request()->get('category') == Str::slug($category['name']) && !request()->has('subcategory') ? 'sidebar-link-active' : '' }}">
                                    {{ $category['name'] }}
                                </a>

                                {{-- Subcategories --}}
                                @if (!empty($category['subcategories']))
                                    @foreach ($category['subcategories'] as $subcategory)
                                        <a href="/shop?category={{ Str::slug($category['name']) }}&subcategory={{ Str::slug($subcategory['name']) }}"
                                            class="sidebar-sub sidebar-link-hover {{ request()->get('subcategory') == Str::slug($subcategory['name']) ? 'sidebar-link-active' : '' }}">
                                            {{ $subcategory['name'] }}
                                        </a>
                                    @endforeach
                                @endif
                            </div>
                        @endforeach
                    </aside>


                    <div class="w-full md:w-4/5 !bg-scroll">
                        <form id="filter-form" action="/shop" method="GET" class="flex flex-wrap gap-3 items-center mb-4">
                            @if (request()->has('category'))
                                <input type="hidden" name="category" value="{{ request()->get('category') }}">
                            @endif
                            @if (request()->has('subcategory'))
                                <input type="hidden" name="subcategory" value="{{ request()->get('subcategory') }}">
                            @endif

                            <!-- Filter: Color Dropdown -->
                            <div class="hidden sm:flex items-center w-full max-md:flex-1 sm:w-auto">
                                <label for="color" class="mr-2 text-sm font-semibold opacity-80">Color:</label>
                                <select id="color" name="color"
                                    class="max-md:flex-1 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                                    <option value="" {{ request()->get('color') == '' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="red" {{ request()->get('color') == 'red' ? 'selected' : '' }}>Red
                                    </option>
                                    <option value="blue" {{ request()->get('color') == 'blue' ? 'selected' : '' }}>Blue
                                    </option>
                                    <option value="green" {{ request()->get('color') == 'green' ? 'selected' : '' }}>
                                        Green
                                    </option>
                                    <option value="black" {{ request()->get('color') == 'black' ? 'selected' : '' }}>
                                        Black
                                    </option>
                                </select>
                            </div>

                            <!-- Filter: Price Range -->
                            <div class="hidden max-md:flex-1 max-w-full sm:flex items-center gap-2">
                                <label class="text-sm font-semibold opacity-80">Price:</label>
                                <input type="number" name="min_price" value="{{ request()->get('min_price') }}"
                                    placeholder="Min"
                                    class="max-md:flex-auto inline-block w-auto md:w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                <span class="text-sm opacity-80">-</span>
                                <input type="number" name="max_price" value="{{ request()->get('max_price') }}"
                                    placeholder="Max"
                                    class="max-md:flex-auto inline-block w-auto md:w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                <button type="submit"
                                    class="max-md:flex-1 bg-black text-white py-2 px-4 text-sm rounded hover:bg-amber-100 hover:text-black transition">Apply</button>
                            </div>

                            {{-- Filter button  --}}
                            <button class="md:hidden inline-flex items-center gap-2">
                                <x-ionicon-filter color="" class="size-4 text-primary" />
                                <span class="">Filter</span>
                            </button>
                            <!-- Sort By Dropdown -->
                            <div class=" ml-auto flex items-center gap-2 sm:gap-4">
                                {{-- <p class="mr-auto md:hidden">Showing {{ $products->firstItem() }} – {{ $products->lastItem() }} of {{ $products->total() }} results</p> --}}
                                <label for="sort" class="mr-2 text-sm font-semibold opacity-80">Sort by:</label>
                                <select id="sort" name="sort"
                                    class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                                    <option value="" {{ request()->get('sort') == '' ? 'selected' : '' }}>Latest
                                    </option>
                                    <option value="oldest" {{ request()->get('sort') == 'oldest' ? 'selected' : '' }}>
                                        Oldest</option>
                                    <option value="price-asc"
                                        {{ request()->get('sort') == 'price-asc' ? 'selected' : '' }}>Price: Low to High
                                    </option>
                                    <option value="price-desc"
                                        {{ request()->get('sort') == 'price-desc' ? 'selected' : '' }}>Price: High to Low
                                    </option>
                                    {{-- <option value="name-asc" {{ request()->get('sort') == 'name-asc' ? 'selected' : '' }}>Name: A-Z</option> --}}
                                    {{-- <option value="name-desc" {{ request()->get('sort') == 'name-desc' ? 'selected' : '' }}>Name: Z-A</option> --}}
                                </select>

                                @if (request()->hasAny(['color', 'min_price', 'max_price', 'sort']))
                                    <button id="reset-filters" type="button"
                                        class="ml-auto text-sm p-2 border border-slate-300 opacity-80 hover:opacity-100 focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-opacity-50 cursor-pointer transition duration-200">
                                        Clear Filters
                                    </button>
                                @endif
                            </div>

                            <!-- Reset Filters Button -->
                            {{-- <div class="flex items-center">
              <button type="button" id="reset-filters" class="bg-gray-200 text-black py-2 px-4 text-sm rounded hover:bg-gray-300 hover:text-black transition">Reset Filters</button>
            </div> --}}
                        </form>

                        @if ($products->isEmpty())
                            <div class="col-span-1 sm:col-span-2 md:col-span-3 lg:col-span-4 text-center py-12">
                                <div class="bg-white border border-slate-200 rounded-lg p-8 max-w-2xl mx-auto">
                                    <h4 class="text-xl font-semibold text-black mb-2">No Products Found</h4>
                                    <p class="text-black text-sm opacity-80 mb-4">
                                        It looks like no products match your current filters. Try adjusting your filters or
                                        browse all available products.
                                    </p>
                                    <a href="{{ route('shop') }}"
                                        class="inline-block py-2 px-4 text-sm font-semibold text-black border border-slate-200 rounded hover:bg-black hover:text-white transition">
                                        Browse All Products
                                    </a>
                                </div>
                            </div>
                        @else
                            <div role="list" id="products-listing"
                                class="product-list grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-3 md:gap-6 py-2 gap-y-6">
                                @foreach ($products as $product)
                                    <x-product-card :product="$product" :href="route('shop.product', ['product' => $product->slug])" />
                                @endforeach
                            </div>

                            @if ($products->hasPages())
                                <div class="flex justify-center items-center gap-2 mt-8">
                                    <a href="{{ $products->appends(request()->query())->previousPageUrl() }}"
                                        class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }} mr-auto">
                                        Prev
                                    </a>

                                    @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                        <a href="{{ $url . (request()->query() ? '&' . http_build_query(request()->query()) : '') }}"
                                            class="py-2 px-3 text-sm border border-slate-200 rounded {{ $products->currentPage() == $page ? 'bg-black text-white' : 'bg-white hover:bg-black hover:text-white transition' }}">
                                            {{ $page }}
                                        </a>
                                    @endforeach

                                    <a href="{{ $products->appends(request()->query())->nextPageUrl() }}"
                                        class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }} ml-auto">
                                        Next
                                    </a>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </section>
        </div>
        <div class="container py-16">
            <div class="h-96 py-16 relative flex flex-center bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/decorative-bg.jpg') }}');">
                <div class="gradient-element"></div>
            </div>
        </div>
    </article>

    @if (session('error'))
            <x-toast type="error" message="{{ session('error') }}" />
        @endif
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const sortSelect = document.getElementById('sort');
            const colorSelect = document.getElementById('color');
            const resetButton = document.getElementById('reset-filters');
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');
            const sidebarTrigger = document.getElementById('sidebar-trigger')
            // Auto-submit form when sort select value changes
            sortSelect.addEventListener('change', () => {
                form.submit();
            });

            // Auto-submit form when color select value changes
            colorSelect.addEventListener('change', () => {
                form.submit();
            });

            // Handle reset filters button
            resetButton?.addEventListener('click', () => {
                // Clear form inputs
                colorSelect.value = '';
                sortSelect.value = '';
                minPriceInput.value = '';
                maxPriceInput.value = '';
                // Navigate to current page without any query parameters
                window.location.href = window.location.pathname;
            });

            // Handle category links to maintain other query parameters
            // document.querySelectorAll('aside a').forEach(link => {
            //     link.addEventListener('click', function(e) {
            //         e.preventDefault();
            //         const url = new URL(this.href);
            //         const params = new URLSearchParams(window.location.search);

            //         // Preserve existing query parameters except category
            //         params.delete('category');
            //         params.delete('subcategory');
            //         url.searchParams.forEach((value, key) => {
            //             params.set(key, value);
            //         });

            //         window.location.href = `/shop?${params.toString()}`;
            //     });
            // });

            // Show categories list on mobile
            sidebarTrigger?.addEventListener('click', (e) => {
                e.preventDefault();
                const sidebar = document.getElementById('shop-sidebar')
                if (sidebar) {
                    sidebar.classList.toggle('sidebar-active')
                } else {
                    console.warn('Sidebar identifier missing')
                }
            })
        });

    </script>
@endsection
