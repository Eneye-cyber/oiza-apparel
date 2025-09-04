@extends('layouts.main')

@section('title', 'Shop')

@section('content')
    <article class="pb-12">
        <div class="bg-gold py-5">
            <div class="container text-white">
                <dl class="flex gap-2.5">
                    <a href="/"
                        class="inline-block pr-2.5 border-r border-cream text-sm opacity-75 hover:opacity-100">Home</a>
                    <a href="/shop" class="inline-block pr-2.5 text-sm font-semibold">Products</a>
                </dl>
            </div>
        </div>
        <div class="container">
            <div class="py-12 text-center">
                <h2 class="text-6xl leading-[0.9] font-playfair-display">Shop</h2>
            </div>

            <section class="flex flex-col flex-between">
                <div class="flex items-start flex-wrap w-full">
                    <aside
                        class="grid grid-cols-2 sm:grid-cols-3 w-full md:w-1/5 md:sticky md:top-14 md:flex flex-col items-start gap-3.5 md:gap-1 mb-10  !bg-scroll">
                        <a href="/shop"
                            class="text-center text-white bg-black md:text-left md:bg-white md:text-black block py-2.5 md:pr-8 text-sm font-semibold {{ !request()->has('category') ? 'bg-black text-white md:text-primary' : '' }}">
                            All products
                        </a>

                        @foreach ($categories as $category)
                            <div class="flex flex-col w-full">
                                <a href="/shop?category={{ Str::slug($category['name']) }}"
                                    class="text-center md:text-left bg-white block py-2.5 md:pr-8 text-sm font-semibold opacity-80 md:hover:underline md:hover:font-semibold max-md:hover:bg-black max-md:hover:text-white hover:opacity-100 {{ request()->get('category') == Str::slug($category['name']) && !request()->has('subcategory') ? 'bg-black text-white md:text-primary' : '' }}">
                                    {{ $category['name'] }}
                                </a>
                                @if (!empty($category['subcategories']))
                                    @foreach ($category['subcategories'] as $subcategory)
                                        <a href="/shop?category={{ Str::slug($category['name']) }}&subcategory={{ Str::slug($subcategory['name']) }}"
                                            class="hidden md:block text-center md:text-left bg-white py-2 md:pr-8 md:pl-6 text-xs md:text-sm opacity-60 md:hover:underline md:hover:font-semibold max-md:hover:bg-black max-md:hover:text-white hover:opacity-100 hover:font-semibold {{ request()->get('subcategory') == Str::slug($subcategory['name']) ? 'bg-black text-white md:text-primary' : '' }}">
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
                            <div class="flex items-center">
                                <label for="color" class="mr-2 text-sm font-semibold opacity-80">Color:</label>
                                <select id="color" name="color"
                                    class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                                    <option value="" {{ request()->get('color') == '' ? 'selected' : '' }}>All
                                    </option>
                                    <option value="red" {{ request()->get('color') == 'red' ? 'selected' : '' }}>Red
                                    </option>
                                    <option value="blue" {{ request()->get('color') == 'blue' ? 'selected' : '' }}>Blue
                                    </option>
                                    <option value="green" {{ request()->get('color') == 'green' ? 'selected' : '' }}>Green
                                    </option>
                                    <option value="black" {{ request()->get('color') == 'black' ? 'selected' : '' }}>Black
                                    </option>
                                </select>
                            </div>

                            <!-- Filter: Price Range -->
                            <div class="flex items-center gap-2">
                                <label class="text-sm font-semibold opacity-80">Price:</label>
                                <input type="number" name="min_price" value="{{ request()->get('min_price') }}"
                                    placeholder="Min"
                                    class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                <span class="text-sm opacity-80">-</span>
                                <input type="number" name="max_price" value="{{ request()->get('max_price') }}"
                                    placeholder="Max"
                                    class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                <button type="submit"
                                    class="bg-black text-white py-2 px-4 text-sm rounded hover:bg-amber-100 hover:text-black transition">Apply</button>
                            </div>

                            <!-- Sort By Dropdown -->
                            <div class="ml-auto flex items-center gap-4">
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
                                class="product-list grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-2">
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('filter-form');
            const sortSelect = document.getElementById('sort');
            const colorSelect = document.getElementById('color');
            const resetButton = document.getElementById('reset-filters');
            const minPriceInput = document.querySelector('input[name="min_price"]');
            const maxPriceInput = document.querySelector('input[name="max_price"]');

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
        });
    </script>
@endsection
