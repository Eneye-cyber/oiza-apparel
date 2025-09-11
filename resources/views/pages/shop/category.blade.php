@extends('layouts.main')

@section('title', $meta['title'])
@section('description', $meta['description'])
@section('keywords', $meta['keywords'])
@section('og_title', $meta['title'])
@section('og_description', $meta['description'])
@section('og_image', $category->image ? asset('storage/' . $category->image) :
    asset('/favicon/android-chrome-192x192.png'))


@section('content')
    <article class="pb-12">
        <div class="bg-gold py-5 text-white">
            <div class="container">
                <nav aria-label="breadcrumb">
                    <ol itemscope itemtype="https://schema.org/BreadcrumbList" class="flex gap-2.5">

                        {{-- Home --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="/" class="breadcrumb-link breadcrumb-link-divider">
                                <span itemprop="name">Home</span>
                            </a>
                            <meta itemprop="position" content="1" />
                        </li>

                        {{-- Products --}}
                        <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                            <a itemprop="item" href="/shop" class="breadcrumb-link breadcrumb-link-divider">
                                <span itemprop="name">Products</span>
                            </a>
                            <meta itemprop="position" content="2" />
                        </li>

                        {{-- Dynamic Breadcrumbs --}}
                        @foreach ($breadcrumbs as $index => $breadcrumb)
                            <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
                                <a itemprop="item" href="{{ url('/shop/' . $breadcrumb['slug']) }}"
                                    class="breadcrumb-link {{ $index < count($breadcrumbs) - 1 ? 'breadcrumb-link-divider' : 'breadcrumb-link-active' }}">
                                    <span itemprop="name">{{ $breadcrumb['name'] }}</span>
                                </a>
                                <meta itemprop="position" content="{{ $index + 3 }}" />
                            </li>
                        @endforeach

                    </ol>
                </nav>

            </div>
        </div>
        <div class="container py-6 md:py-12">

            <section class="flex flex-col flex-between">
                <div class="flex items-start flex-wrap w-full">
                    <!-- Aside sidebar -->
                    <aside
                        class="hidden w-full md:w-1/5 md:sticky md:top-14 md:flex flex-col items-start gap-3.5 md:gap-1 md:mb-10 md:pr-6 !bg-scroll">


                        <div class="grid grid-cols-2 sm:grid-cols-3 md:flex flex-col md:gap-4 w-full">
                            <a href="{{ url('/shop/' . $sidebarCategories['parent']['full_slug_path']) }}"
                                class="side-nav-link {{ request()->route('slug') === $sidebarCategories['parent']['full_slug_path']
                                    ? 'side-nav-link-active'
                                    : 'side-nav-link-inactive' }}">
                                {{ $sidebarCategories['parent']['name'] }}
                            </a>

                            <!-- Subcategories (indented, slightly smaller on mobile) -->
                            @if (!empty($sidebarCategories['children']))
                                @foreach ($sidebarCategories['children'] as $subcategory)
                                    <a href="{{ url('/shop/' . $subcategory['full_slug_path']) }}"
                                        class="side-nav-link {{ request()->route('slug') === $subcategory['full_slug_path']
                                            ? 'side-nav-link-active'
                                            : 'side-nav-link-inactive' }}">
                                        {{ $subcategory['name'] }}
                                    </a>
                                @endforeach
                            @endif
                        </div>

                        <div class="separator my-3 md:my-6"></div>

                        <form id="filter-form" action="" method="GET" class="flex flex-col gap-6 md:my-4">
                            @if (request()->has('subcategory'))
                                <input type="hidden" name="subcategory" value="{{ request()->get('subcategory') }}">
                            @endif
                            <!-- Filters and Sort By Controls -->
                            <!-- Filter: Color Dropdown -->
                            <div class="hidden md:flex items-center md:items-start md:flex-col gap-2.5">
                                <label for="color-filter" class="mr-2 text-sm font-semibold opacity-80">Color:</label>
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
                            <div class="hidden md:flex items-center md:items-start md:flex-col gap-2.5">
                                <label class="text-sm font-semibold opacity-80">Price:</label>
                                <div class="flex flex-between gap-2">
                                    <input type="number" placeholder="Min" name="min_price"
                                        value="{{ request()->get('min_price') }}"
                                        class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                    <span class="text-sm opacity-80">-</span>
                                    <input type="number" name="max_price" value="{{ request()->get('max_price') }}"
                                        placeholder="Max"
                                        class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
                                </div>
                                <button type="submit"
                                    class="w-full mt-2 bg-black text-white py-2 px-4 text-sm rounded hover:bg-amber-100 hover:text-black transition">Apply</button>
                            </div>

                        </form>

                    </aside>

                    <!-- listings  -->
                    <div class="w-full md:w-4/5 !bg-scroll">
                        <div class="text-left">
                            <h2 class="page-title capitalize">{{ $category['name'] }}</h2>
                            <p class="mt-2.5 opacity-80">
                                {{ $category->description ?? 'Whether casual or formal, find the perfect jewelry for every occasion with us.' }}
                            </p>

                            <div class="flex flex-wrap gap-3 mt-3">
                                @forelse ($category->children as $child)
                                    <span
                                        class="px-4 py-1.5 border border-slate-200 rounded-full text-sm hover:bg-slate-800 hover:text-white focus:outline-none focus:ring-2 focus:ring-slate-400 focus:ring-opacity-50 cursor-pointer transition duration-200">
                                        <a href="{{ url('/shop/' . $child->full_slug_path) }}">{{ $child->name }}</a>
                                    </span>
                                @empty
                                @endforelse
                            </div>
                        </div>

                        <div class="separator my-6 mt-4"></div>

                        <div class="flex flex-col gap-3 mb-4">
                            <!-- Sort By Dropdown (aligned to right on larger screens) -->
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

                        </div>
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



                        <!-- Pagination -->
                        <!-- ($products->hasPages()) -->
                        @if ($products->count() > 20)
                            <div class="flex justify-center items-center gap-2 mt-8">
                                <!-- Previous Button -->
                                <a href="{{ $products->previousPageUrl() }}"
                                    class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
                                    Prev
                                </a>

                                <!-- Page Numbers -->
                                @foreach ($products->getUrlRange(1, $products->lastPage()) as $page => $url)
                                    <a href="{{ $url }}"
                                        class="py-2 px-3 text-sm border border-slate-200 rounded {{ $products->currentPage() == $page ? 'bg-black text-white' : 'bg-white hover:bg-black hover:text-white transition' }}">
                                        {{ $page }}
                                    </a>
                                @endforeach

                                <!-- Next Button -->
                                <a href="{{ $products->nextPageUrl() }}"
                                    class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
                                    Next
                                </a>
                            </div>
                        @endif
                    </div>

                </div>
            </section>
        </div>
        <!-- Promotional banner  -->
        <div class="container py-16">
            <!-- https://www.freepik.com/premium-photo/decorative-pattern-fabric-thai-traditional-style-fabric_22163297.htm#fromView=search&page=1&position=35&uuid=d46d82ac-4ff4-4acf-9f0c-ff7b6255c90a&query=ankara+print -->
            <div class="h-96 py-16 relative flex flex-center bg-center bg-no-repeat"
                style="background-image: url('{{ asset('img/decorative-bg.jpg') }}');">
                <div class="gradient-element"></div>
            </div>
        </div>
    </article>
@endsection

@section('scripts')
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
