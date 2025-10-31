@php
    $productId = $this->getState();
    $product = \App\Models\Products\Product::find($productId);
@endphp

@if($product && $product->cover_media)
    <img 
        src="{{ $product->cover_media_url }}" 
        alt="{{ $product->name }}"
        class="w-12 h-12 object-cover rounded"
    >
@else
    <div class="w-12 h-12 bg-gray-200 rounded flex items-center justify-center">
        <span class="text-gray-400 text-xs">No Image</span>
    </div>
@endif