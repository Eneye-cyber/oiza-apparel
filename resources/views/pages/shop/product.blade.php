@extends('layouts.main')
@section('title', $productModel['name'])
@section('keywords', htmlspecialchars($tags_string))
@section('content')
<article class="py-10">
  <div class="container">

    <section class="flex flex-col flex-between">
      <div class="flex items-start w-full">
        <!-- Main Content -->
        <div class="w-full !bg-scroll">
          <div class="flex flex-col md:flex-row gap-6">
            <!-- Product Image -->
            <div class="md:w-1/2 md:sticky md:top-14">
              <div class="flex items-start justify-center h-[360px] md:h-[480px] w-full transition-all duration-300 border border-slate-200 2xl:border-[#fafafa] group-hover:border-amber-100 overflow-hidden">
                <img src="{{ $productModel['image'] }}" loading="lazy" alt="{{ $productModel['name'] }}" class="object-scale w-auto h-full transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
              </div>
              <div class="separator my-3 bg-gold"></div>
              <div class="flex items-center gap-3 h-32">
                @for ($i = 0; $i < 4; $i++)
                  <div class="flex-1 max-w-1/4 h-full {{ $i === 0 ? 'border-2 border-gold' : ' cursor-pointer'}}">
                  <img src="https://www.ankara.com.ng/wp-content/uploads/2025/08/Blue-Isi-agu-Traditional-Fabric-Per-Yard-600x450.png.webp" loading="lazy" alt="{{ $productModel['name'] }}" class="object-scale w-full h-full transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
              </div>
              @endfor

            </div>
          </div>
          <!-- Product Details -->
          <div class="md:w-1/2 flex flex-col gap-4 px-6">
            <!-- Breadcrumbs -->
            <nav class="flex gap-2.5 md:gap-4 mb-6">
              <!-- <a href="/" class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">Home</a> -->
              <a href="/shop" class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">Shop</a>
              <a href="{{ url('/shop/' . Str::slug($productModel['category']['name'])) }}" class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">{{ $productModel['category']['name'] }}</a>
              @if($productModel['subcategory'])
              <a href="{{ url('/shop/' . Str::slug($productModel['category']['name']) . '/' . Str::slug($productModel['subcategory']['name'])) }}" class="inline-block pr-2.5 md:pr-4 border-r border-gold text-sm opacity-75 hover:opacity-100">{{ $productModel['subcategory']['name'] }}</a>
              @endif
              <span class="inline-block pr-2.5 text-sm font-semibold">{{ $productModel['name'] }}</span>
              <!-- <a href="" class="inline-block pr-2.5  text-sm font-semibold">Latest arrival</a> -->
            </nav>


            <div class="space-y-1">
              <h3 class="text-gold text-lg tracking-wider">
                {{ isset($productModel['subcategory']) ? $productModel['subcategory']['name'] : $productModel['category']['name'] }}
              </h3>
              <h1 class="text-4xl md:text-5xl xl:text-6xl font-playfair-display">{{ $productModel['name'] }}</h1>

            </div>
            <p class="text-xl font-semibold">$ {{ number_format($productModel['price'], 2) }} USD</p>
            <p class="opacity-80">{{ $productModel['description'] }}</p>
            <!-- Attributes (e.g., Color, Size) -->
            <div class="flex flex-col gap-2">
              <div class="flex items-center gap-2">
                <label for="color" class="text-sm font-semibold opacity-80">Color:</label>
                <select id="color" class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                  <option value="red">Red</option>
                  <option value="blue">Blue</option>
                  <option value="green">Green</option>
                </select>
              </div>
              <div class="flex items-center gap-2">
                <label for="size" class="text-sm font-semibold opacity-80">Size:</label>
                <select id="size" class="bg-white border border-slate-200 py-2 px-3 text-sm rounded hover:bg-black hover:text-white transition">
                  <option value="s">Small</option>
                  <option value="m">Medium</option>
                  <option value="l">Large</option>
                </select>
              </div>
            </div>

            <div class="separator my-4"></div>
            <!-- Quantity and Add to Cart -->
            <div class="flex flex-between gap-4">
              <label for="quantity" class="text-lg font-semibold mb-1.5">Quantity</label>
              <div class="flex items-center border border-slate-200 rounded">
                <button class="px-3 py-2 text-sm hover:bg-black hover:text-white transition">-</button>
                <input type="number" id="quantity" value="1" min="1" class="w-16 text-center border-x border-slate-200 py-2 text-sm !appearance-none">
                <button class="px-3 py-2 text-sm hover:bg-black hover:text-white transition">+</button>
              </div>
            </div>
            <button class="btn-solid uppercase">Add to Cart</button>

            <!-- Category and Tags -->
            <div class="py-6 space-y-4">
              <p><strong>Category</strong>: {{ isset($productModel['subcategory']) ? $productModel['category']['name'] . ', '. $productModel['subcategory']['name'] :  $productModel['category']['name'] }}</p>
              <p class="line-clamp-4 text-balance"><strong>Tags</strong>: {{$tags_string}}</p>

            </div>
          </div>
        </div>
      </div>
  </div>
  </section>
  </div>
</article>
@endsection