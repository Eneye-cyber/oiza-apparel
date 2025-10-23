@extends('layouts.main')

@section('title', $product['name'])
@section('keywords', htmlspecialchars($tags_string))
@section('description', Str::limit(strip_tags($product['description']), 160))

@section('modal')
  <x-modals.quick-view />
@endsection

@section('content')
<article class="py-10">
  <div class="container">
    <section class="flex flex-col flex-between">
      <div class="flex items-start w-full">
        <!-- Main Content -->
        <div class="w-full !bg-scroll">
          <div class="flex flex-col md:flex-row gap-y-6">

            <!-- Product Image -->
            <div class="md:w-1/2 md:sticky md:top-14">
              <div class="flex flex-col md:px-3 gap-3">

                <!-- Main Image -->
                <div class="flex h-[22.5rem] md:h-[32.5rem] w-full overflow-hidden">
                  <img id="main-image"
                       src="{{ $product->cover_media_url }}"
                       loading="eager"
                       alt="{{ $product['name'] }}"
                       class="object-cover w-full h-auto transition-all duration-300">
                </div>

                <!-- Thumbnails -->
                <div class="flex items-center justify-start  gap-3">
                  @php
                    $allImages = [$product->cover_media_url];
                    if (!empty($product->media)) {
                      $allImages = array_merge($allImages, $product->media);
                    }
                    $variantImages = array_filter(array_column($product->variants->toArray(), 'media_url'));
                    $allImages = array_merge($allImages, $variantImages);
                    $allImages = array_unique($allImages);
                  @endphp

                  @foreach ($allImages as $index => $image)
                    @php
                      $variant = $product->variants->firstWhere('media_url', $image);
                    @endphp
                    <button type="button"
                            class="{{ count($allImages) < 3 ? 'w-42' : 'flex-1' }} h-42 object-contain overflow-hidden thumbnail {{ $index === 0 ? 'selected' : '' }}"
                            aria-label="Select image {{ $index + 1 }}"
                            data-image="{{ $image }}"
                            data-variant-id="{{ $variant->id ?? '' }}">
                      <img src="{{ $image }}"
                           loading="lazy"
                           alt="{{ $product->name }}"
                           class="object-contain w-full h-auto transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                    </button>
                  @endforeach
                </div>
              </div>
            </div>

            <!-- Product Details -->
            <div class="md:w-1/2 flex flex-col gap-4 md:px-6">

              <!-- Breadcrumbs -->
              <x-breadcrumbs :breadcrumbs="$breadcrumbs" :productName="$product['name']" />

              <!-- Title & Category -->
              <div class="space-y-1">
                <h3 class="text-gold text-lg tracking-wider">
                  {{ $product['subcategory']['name'] ?? $product['category']['name'] }}
                </h3>
                <h1 class="text-3xl xl:text-5xl font-playfair-display">{{ $product['name'] }}</h1>
              </div>

              <!-- Price -->
              <p class="text-xl font-semibold tracking-wider">₦ {{ number_format($product['price'], 2) }}</p>

              <!-- Description -->
              <div class="opacity-80">{!! \Filament\Forms\Components\RichEditor\RichContentRenderer::make($product['description'])->toHtml() !!}</div>

              <!-- Attributes -->
              <x-product-variants :variants="$product->variants" :baseImage="$product->cover_media_url" :baseName="$product['name']" :baseId="$product->id" />
              {{-- <x-product-attributes :variants="$product" /> --}}

              <div class="separator my-4"></div>

              <!-- Quantity & Add to Cart -->
              <div class="flex flex-between gap-4">
                <label for="quantity" class="text-lg font-semibold mb-1.5">Quantity</label>
                <div class="flex items-center border border-slate-200 rounded">
                  <button type="button" class="qty-btn px-3 py-2 text-sm" data-change="-1">-</button>
                  <input type="number" id="quantity" value="1" min="1" max="{{ $product->max_quantity }}"
                         class="w-16 text-center border-x border-slate-200 py-2 text-sm !appearance-none">
                  <button type="button" class="qty-btn px-3 py-2 text-sm" data-change="1">+</button>
                </div>
              </div>

              <button class="btn tracking-widest uppercase add-to-cart relative group hover:border-primary cursor-pointer"
                      data-product-id="{{ $product->id }}">
                <span class="transition-all duration-300 z-10 group-hover:text-white relative">Add to Cart</span>
                <div data-product-id="{{ $product->id }}"
                    class="transition-all duration-300 z-0 bg-primary rounded-full w-full aspect-square absolute top-full left-0 group-hover:scale-110 group-hover:-translate-y-1/2  transform-style-3d">
                </div>
              </button>

              <button class="btn-solid tracking-widest uppercase buy-now"
                      data-product-id="{{ $product->id }}">
                Buy it Now
              </button>

              <!-- Category & Tags -->
              <div class="py-6 space-y-4">
                <p><strong>Category</strong>: {{ $product['category']['name'] }}{{ isset($product['subcategory']) ? ', '. $product['subcategory']['name'] : '' }}</p>
                <p class="line-clamp-4 text-balance"><strong>Tags</strong>: {{ $tags_string }}</p>
              </div>
            </div>

          </div>
        </div>

      </div>


    <div class="separator mt-8 mb-4"></div>
    <!-- Featured Collection  -->
    <article class="py-8 w-full">
      <div class="flex justify-between items-baseline flex-wrap" data-aos="fade-up" data-aos-delay="100"
          data-aos-anchor-placement="top-bottom">
          <h3 class="section-title-text text-black/80">Related Products</h3>
          <a href="{{ route('shop') }}" class="flex flex-center gap-2 transition-all group hover:text-primary">
              <h5 class="font-medium">View Full Collection</h5>
              <x-heroicon-c-arrow-right class="h-3.5 w-auto stroke-2 text-primary group-hover:text-black" />
          </a>
      </div>
      
      <section role="list" id="related-products"
          class="product-list grid sm:grid-cols-2 xl:grid-cols-4 gap-8 py-14">
          @foreach ($similarProducts as $product)
              <x-product-card :product="$product" :href="route('shop.product', ['product' => $product['slug']])" />
          @endforeach
      </section>
      

    </article>
    </section>
  </div>
</article>
@endsection

@section('scripts')
<script>
  window.ProductPage = {
    variants: @json($product->variants),
    maxQuantity: {{ $product->max_quantity }},
    productName: @json($product->name),
  };
</script>
@vite('resources/js/product.js')
@endsection
