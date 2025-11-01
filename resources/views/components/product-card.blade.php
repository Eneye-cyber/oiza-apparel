@props([
  'product' => null,
  'href' => '#',
  'imageSrc' => null,
  'imageAlt' => null,
  'name' => null,
  'price' => null,
  'class' => 'product-list-item',
])

@php
  $imageSrc = $imageSrc ?? ($product['cover_media_url'] ?? '');
  $variants = $product['variants'] ?? [];
  $imageAlt = $imageAlt ?? ($product['name'] ?? '');
  $name = $name ?? ($product->name ?? '');
  $price = $price ?? ($product['price'] ?? '');
  $productId = $product->id ?? null;
@endphp

<div id="product-{{ $productId }}" {{ $attributes->merge(['class' => $class, 'role' => 'listitem']) }}>
  <a href="{{ $href }}" class="block w-full group">
    <div class="flex flex-col gap-4">
      
      <!-- Image Container -->
      <div
        class="relative flex items-center justify-center h-48 sm:h-64 md:h-[320px] w-full transition-all duration-300 border border-slate-200 2xl:border-[#fafafa] group-hover:border-primary group-hover:text-primary overflow-hidden">
        
        <img
          id="main-image-{{ $productId }}"
          src="{{ $imageSrc }}"
          loading="lazy"
          alt="{{ $imageAlt }}"
          class="object-contain w-10/12 h-auto transition-all duration-300 group-hover:scale-110"
        >

        <!-- Add to Cart (Desktop) -->
        <button
          data-product-id="{{ $productId }}"
          data-variant-id=""
          class="add-to-cart max-md:hidden translate-y-full group-hover:translate-y-0 transition-all duration-300 text-white font-semibold gap-2.5 bg-primary border border-primary w-full py-2 px-4 flex flex-center absolute bottom-0 left-1/2 -translate-1/2 overflow-hidden">
          <span class="text-sm text-white">ADD TO CART</span>
        </button>
      </div>

      <!-- Product Name + Price -->
      <div class="text-center px-1.5">
        <h4 class="text-sm max-md:line-clamp-3 sm:text-base md:text-base font-medium group-hover:underline">{{ $name }}</h4>
        <p class="opacity-70">₦ {{ number_format($price, 2) }}</p>
      </div>

      <!-- Variants -->
      @if(count($variants) != 0)
        <div class="flex justify-center space-x-1 pb-1">
          <!-- Default (base) image button -->
          <button
            type="button"
            class="variant-btn"
            data-variant-id="0"
            data-image-src="{{ $imageSrc }}">
            <img src="{{ $imageSrc }}" alt="{{ $imageAlt }}" class="w-8 h-8 object-cover">
          </button>

          <!-- Variant buttons -->
          @foreach($variants as $index => $variant)
            <button
              type="button"
              class="variant-btn "
              data-variant-id="{{ $variant['id'] }}"
              data-image-src="{{ $variant['media_url'] }}">
              <img src="{{ $variant['media_url'] }}" alt="{{ $variant['name'] }}" class="w-8 h-8 object-cover">
            </button>
          @endforeach
        </div>
      @endif

      <!-- Add to Cart (Mobile) -->
      <button
        data-product-id="{{ $productId }}"
        data-variant-id=""
        class="add-to-cart md:hidden text-white font-semibold gap-2.5 bg-primary border border-primary w-full py-2 px-4 flex flex-center overflow-hidden">
        <span class="text-sm text-white">ADD TO CART</span>
      </button>

    </div>
  </a>
</div>
