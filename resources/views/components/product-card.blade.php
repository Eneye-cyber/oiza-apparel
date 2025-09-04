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
  // Fallback to $product properties if individual props are not provided
  $imageSrc = $imageSrc ?? ($product['cover_media'] ?? '');
  $imageAlt = $imageAlt ?? ($product['name'] ?? '');
  $name = $name ?? ($product->name ?? '');
  $price = $price ?? ($product['price'] ?? '');
  $productId = $product->id ?? null; // Assuming product has an ID for AJAX
@endphp

<div {{ $attributes->merge(['class' => $class, 'role' => 'listitem']) }}>
  <a href="{{ $href }}" class="block w-full group">
    <div class="flex flex-col gap-4">
      <div
        class="relative flex items-center justify-center h-64 md:h-[360px] w-full transition-all duration-300 border border-slate-200 2xl:border-[#fafafa] group-hover:border-primary group-hover:text-primary overflow-hidden">
        <img src="{{ $imageSrc }}" loading="lazy" alt="{{ $imageAlt }}"
          class="object-contain w-10/12 h-auto transition-all duration-300 group-hover:scale-110 ">

        <!-- Quick View Button -->
        {{-- 
        <button 
          class="quick-view-btn z-10 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-300 font-semibold gap-2.5 bg-white shadow-sm w-auto p-1 rounded-sm flex flex-center absolute top-2 right-2 overflow-hidden"
          data-product-slug="{{ $product->slug }}"
        >
          <span class="text-sm text-black"><x-heroicon-o-eye class="h-3.5 w-auto" /></span>
        </button> 
        --}}

        <button data-product-id="{{ $productId }}"
          class="translate-y-full group-hover:translate-y-0 transition-all duration-300 text-white font-semibold gap-2.5 bg-black border border-black w-full py-2 px-4 flex flex-center absolute bottom-0 left-1/2 -translate-1/2 overflow-hidden">
          <span class="text-sm text-white">ADD TO CART</span>
        </button>
      </div>

      <div class="text-center px-1.5">
        <h4 class="text-lg font-semibold group-hover:underline">{{ $name }}</h4>
        <p class="opacity-80">₦ {{ $price }} NGN</p>
      </div>
    </div>
  </a>
</div>
