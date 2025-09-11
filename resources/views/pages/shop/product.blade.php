@extends('layouts.main')

@section('title', $product['name'])
@section('keywords', htmlspecialchars($tags_string))
@section('description', Str::limit(strip_tags($product['description']), 160))

@section('content')
<article class="py-10">
  <div class="container">
    <section class="flex flex-col flex-between">
      <div class="flex items-start w-full">
        <!-- Main Content -->
        <div class="w-full !bg-scroll">
          <div class="flex flex-col md:flex-row gap-y-6">

            <!-- Product Image -->
            <div class="md:w-1/2 md:sticky max-h-[500px] md:top-14">
              <div class="flex flex-col md:flex-row-reverse md:items-start">

                <!-- Main Image -->
                <div class="flex items-start justify-start px-3 h-[360px] md:h-auto w-full overflow-hidden">
                  <img id="main-image"
                       src="{{ Storage::disk(env('APP_DISK', 'local'))->url($product->cover_media) }}"
                       loading="eager"
                       alt="{{ $product['name'] }}"
                       class="object-contain w-full h-full transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                </div>

                <!-- Thumbnails -->
                <div class="flex md:flex-col md:w-2/12 items-center gap-3">
                  @php
                    $allImages = [$product->cover_media];
                    if (!empty($product->media)) {
                      $allImages = array_merge($allImages, $product->media);
                    }
                    $variantImages = array_filter(array_column($product->variants->toArray(), 'image'));
                    $allImages = array_merge($allImages, $variantImages);
                    $allImages = array_unique($allImages);
                  @endphp

                  @foreach ($allImages as $index => $image)
                    @php
                      $variant = $product->variants->firstWhere('image', $image);
                    @endphp
                    <button type="button"
                            class="flex-1 w-full h-42 object-contain thumbnail {{ $index === 0 ? 'border-2 border-gold' : 'cursor-pointer' }}"
                            aria-label="Select image {{ $index + 1 }}"
                            data-image="{{ Storage::disk(env('APP_DISK', 'local'))->url($image) }}"
                            data-variant-id="{{ $variant->id ?? '' }}">
                      <img src="{{ Storage::disk(env('APP_DISK', 'local'))->url($image) }}"
                           loading="lazy"
                           alt="{{ $product->name }}"
                           class="object-scale w-auto h-full transition-all duration-300 group-hover:scale-110 group-hover:rotate-6">
                    </button>
                  @endforeach
                </div>
              </div>
            </div>

            <!-- Product Details -->
            <div class="md:w-1/2 flex flex-col gap-4 px-6">

              <!-- Breadcrumbs -->
              <x-breadcrumbs :breadcrumbs="$breadcrumbs" :productName="$product['name']" />

              <!-- Title & Category -->
              <div class="space-y-1">
                <h3 class="text-gold text-lg tracking-wider">
                  {{ $product['subcategory']['name'] ?? $product['category']['name'] }}
                </h3>
                <h1 class="text-4xl md:text-5xl font-playfair-display">{{ $product['name'] }}</h1>
              </div>

              <!-- Price -->
              <p class="text-xl font-semibold">₦ {{ number_format($product['price'], 2) }} NGN</p>

              <!-- Description -->
              <p class="opacity-80">{{ $product['description'] }}</p>

              <!-- Attributes -->
              <x-product-attributes :variants="$product->variants" />

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

              <button class="btn-solid uppercase add-to-cart"
                      data-product-id="{{ $product->id }}">
                Add to Cart
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
<script src="{{ asset('js/product.js') }}"></script>
@endsection
