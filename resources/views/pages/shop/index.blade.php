@extends('layouts.main')

@section('title', 'Shop')

@section('content')
<article class="pb-12">
  <div class="bg-gold py-5">
    <div class="container text-white">
      <dl class="flex gap-2.5">
        <a href="/" class="inline-block pr-2.5 border-r border-cream text-sm opacity-75 hover:opacity-100">Home</a>
        <a href="/shop" class="inline-block pr-2.5 text-sm font-semibold">Products</a>
        <!-- <a href="" class="inline-block pr-2.5  text-sm font-semibold">Latest arrival</a> -->
      </dl>
    </div>
  </div>
  <div class="container">
    <div class="py-12 text-center">
      <h2 class="text-6xl leading-[0.9] font-playfair-display">Shop</h2>
    </div>

    <section class="flex flex-col flex-between">
      <div class="flex items-start flex-wrap w-full">
        <aside class="grid grid-cols-2 sm:grid-cols-3 w-full md:w-1/5 md:sticky md:top-14 md:flex flex-col items-start gap-3.5 md:gap-1 mb-10  !bg-scroll">
          <a href="/shop" class="text-center text-white bg-black md:text-left md:bg-white md:text-black block py-2.5 md:pr-8 text-sm font-semibold">
            All products
          </a>

          @foreach($categories as $category)
          <div class="flex flex-col w-full">
            <a href="{{ url('/shop/' . Str::slug($category['name'])) }}" class="text-center md:text-left bg-white block py-2.5 md:pr-8 text-sm font-semibold opacity-80 md:hover:underline md:hover:font-semibold max-md:hover:bg-black max-md:hover:text-white hover:opacity-100">
              {{ $category['name'] }}
            </a>
            <!-- Subcategories (indented, slightly smaller on mobile) -->
            @if(!empty($category['subcategories']))
            @foreach($category['subcategories'] as $subcategory)
            <a href="{{ url('/shop/' . Str::slug($category['name']) . '/' . Str::slug($subcategory['name'])) }}" class="hidden md:block text-center md:text-left bg-white  py-2 md:pr-8 md:pl-6 text-xs md:text-sm opacity-60 md:hover:underline md:hover:font-semibold max-md:hover:bg-black max-md:hover:text-white hover:opacity-100 hover:font-semibold">
              {{ $subcategory['name'] }}
            </a>
            @endforeach
            @endif
          </div>
          @endforeach
        </aside>

        <div class="w-full md:w-4/5 !bg-scroll">
          <div class="flex flex-wrap gap-3 items-center mb-4">
            <!-- Filters and Sort By Controls -->
            <!-- Filter: Color Dropdown -->
            <div class="flex items-center">
              <label for="color-filter" class="mr-2 text-sm font-semibold opacity-80">Color:</label>
              <select id="color-filter" class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                <option value="">All</option>
                <option value="red">Red</option>
                <option value="blue">Blue</option>
                <option value="green">Green</option>
                <option value="black">Black</option>
              </select>
            </div>

            <!-- Filter: Price Range -->
            <div class="flex items-center gap-2">
              <label class="text-sm font-semibold opacity-80">Price:</label>
              <input type="number" placeholder="Min" class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
              <span class="text-sm opacity-80">-</span>
              <input type="number" placeholder="Max" class="w-20 bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:border-amber-100 transition">
              <button class="bg-black text-white py-2 px-4 text-sm rounded hover:bg-amber-100 hover:text-black transition">Apply</button>
            </div>

            <!-- Sort By Dropdown (aligned to right on larger screens) -->
            <div class="ml-auto flex items-center">
              <label for="sort-by" class="mr-2 text-sm font-semibold opacity-80">Sort by:</label>
              <select id="sort-by" class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                <option value="default">Default</option>
                <option value="price-asc">Price: Low to High</option>
                <option value="price-desc">Price: High to Low</option>
                <option value="name-asc">Name: A-Z</option>
                <option value="name-desc">Name: Z-A</option>
              </select>
            </div>
          </div>
          <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-2">
            @foreach($products as $product)
            <div role="listitem" class="product-list-item">
              <a href="{{ url('/shop/' . 'category'. '/' . 'product-slug') }}" class="block w-full group">
                <div class="flex flex-col gap-4">
                  <div class="flex items-center justify-center h-[360px] w-full transition-all duration-300 border border-slate-200 2xl:border-[#fafafa] group-hover:border-amber-100 group-hover:text-primary overflow-hidden">
                    <img src="{{ $product['image'] }}" loading="lazy" alt="Ankara" class="object-contain w-10/12 h-auto transition-all duration-300 group-hover:scale-110 group-hover:rotate-6 ">
                  </div>

                  <div class="text-center px-1.5">
                    <h4 class="text-lg font-semibold">{{ $product['name'] }}</h4>
                    <p class="opacity-80">$ {{ $product['price'] }} USD</p>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>

          <div class="grid sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6 py-8">
            @foreach($products as $product)
            <div role="listitem" class="product-list-item">
              <a href="{{ url('/shop/' . 'category'. '/' . 'product-slug') }}" class="block w-full group">
                <div class="flex flex-col gap-4">
                  <div class="flex items-center justify-center h-[360px] w-full transition-all duration-300 border border-slate-200 2xl:border-[#fafafa] group-hover:border-amber-100 group-hover:text-primary overflow-hidden">
                    <img src="{{ $product['image'] }}" loading="lazy" alt="Ankara" class="object-contain w-10/12 h-auto transition-all duration-300 group-hover:scale-110 group-hover:rotate-6 ">
                  </div>

                  <div class="text-center px-1.5">
                    <h4 class="text-lg font-semibold">{{ $product['name'] }}</h4>
                    <p class="opacity-80">$ {{ $product['price'] }} USD</p>
                  </div>
                </div>
              </a>
            </div>
            @endforeach
          </div>

          <!-- Pagination -->
          <!-- ($products->hasPages()) -->
          @if($products->count() > 20)
          <div class="flex justify-center items-center gap-2 mt-8">
            <!-- Previous Button -->
            <a href="{{ $products->previousPageUrl() }}" class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->onFirstPage() ? 'opacity-50 cursor-not-allowed' : '' }}">
              Prev
            </a>

            <!-- Page Numbers -->
            @foreach($products->getUrlRange(1, $products->lastPage()) as $page => $url)
            <a href="{{ $url }}" class="py-2 px-3 text-sm border border-slate-200 rounded {{ $products->currentPage() == $page ? 'bg-black text-white' : 'bg-white hover:bg-black hover:text-white transition' }}">
              {{ $page }}
            </a>
            @endforeach

            <!-- Next Button -->
            <a href="{{ $products->nextPageUrl() }}" class="py-2 px-3 text-sm border border-slate-200 rounded hover:bg-black hover:text-white transition {{ $products->hasMorePages() ? '' : 'opacity-50 cursor-not-allowed' }}">
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
    <div class="h-96 py-16 relative flex flex-center bg-center bg-[url('https://img.freepik.com/premium-photo/decorative-pattern-fabric-thai-traditional-style-fabric_483511-2148.jpg?w=1480')]">
      <div class="gradient-element"></div>
    </div>
  </div>
</article>
@endsection