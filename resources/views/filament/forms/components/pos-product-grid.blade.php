<div class="space-y-4">
  @php
    $search = $this->form->getState()['product_search'] ?? '';
    $products = \App\Models\Products\Product::query()
        ->when($search, function ($query, $search) {
            $query->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%");
        })
        ->limit(12)
        ->get();
  @endphp

  <div class="fi-grid grid-cols-1 md:grid-cols-2 fi-grid-cols-3 gap-4 max-h-96 overflow-y-auto">
    @foreach ($products as $product)
      <div class="border rounded-lg p-3 cursor-pointer hover:bg-gray-50 transition-colors"
        wire:click="$dispatch('add-product-to-order', { productId: {{ $product->id }} })">
        @if ($product->cover_media)
          <img src="{{ $product->cover_media_url }}" alt="{{ $product->name }}"
            class="w-full h-24 object-cover rounded mb-2">
        @else
          <div class="w-full h-24 bg-gray-200 rounded mb-2 flex items-center justify-center">
            <span class="text-gray-400">No Image</span>
          </div>
        @endif

        <h4 class="font-semibold text-sm truncate">{{ $product->name }}</h4>
        <p class="text-xs text-gray-600 truncate">{{ $product->sku }}</p>
        <p class="text-sm font-bold text-primary-600 mt-1">${{ number_format($product->price, 2) }}</p>
{{-- 
        @if ($product->stock_quantity > 0)
          <p class="text-xs text-success-600">In Stock: {{ $product->stock_quantity }}</p>
        @else
          <p class="text-xs text-danger-600">Out of Stock</p>
        @endif --}}
      </div>
    @endforeach
  </div>

  @if ($products->isEmpty())
    <div class="text-center py-8 text-gray-500">
      No products found matching your search.
    </div>
  @endif
</div>
