@props(['variants', 'baseName', 'baseImage', 'baseId'])

@php
  // $attributes = collect($variants)->flatMap(fn($variant) => $variant['attributes'])->groupBy('attribute_name');
@endphp

<div class="relative" id="variantDropdown">

  <!-- Selected Option Display -->
  <button type="button" id="selectTrigger"
    class="w-full max-w-sm flex items-center justify-between p-3 border border-black hover:border-primary focus:outline-none focus:ring-2 focus:ring-primary focus:border-primary transition-all duration-200">
    <div class="flex items-center space-x-3">
      @if (count($variants) > 0)
        <img id="selectedImage" src="" alt=""
          class="hidden w-6 h-6 object-cover border-2 border-primary rounded-full">
        <span id="selectedName" class="text-gray-900 font-medium line-clamp-1">Select an option</span>
      @else
        <img id="selectedImage" src="{{ $baseImage }}" alt=""
          class="w-6 h-6 object-cover border-2 border-primary rounded-full">
        <span id="selectedName" class="text-gray-900 font-medium line-clamp-1">{{ $baseName }}</span>
      @endif
    </div>
    <x-heroicon-o-chevron-down id="dropdownIcon" class="size-6" />
  </button>

  <!-- Dropdown Options -->
  <div id="dropdownOptions"
    class="absolute z-50 w-full max-w-sm mt-1 bg-white border border-gray-300  hidden">
    <div class="max-h-60 overflow-y-auto">
      <div
          class="option-item px-3 py-2 cursor-pointer hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150 flex items-center space-x-3"
          data-value="0">
          <img src="{{ $baseImage }}" alt="{{ $baseName }}"
            class="w-10 h-10 rounded-full object-cover border-2 border-primary">
          <span class="font-medium">{{ $baseName }}</span>
        </div>
      @foreach ($variants as $variant)
        <div
          class="option-item px-3 py-2 cursor-pointer hover:bg-blue-50 hover:text-blue-700 transition-colors duration-150 flex items-center space-x-3"
          data-value="{{ $variant['id'] }}">
          <img src="{{ $variant['media_url'] }}" alt="{{ $variant['name'] }}"
            class="w-10 h-10 rounded-full object-cover border-2 border-primary">
          <span class="font-medium">{{ $variant['name'] }}</span>
        </div>
      @endforeach
    </div>
  </div>
</div>

<!-- Hidden native select for form submission -->
<select id="nativeSelect" name="variant_id" class="hidden">
  <option value="">Select an option</option>
  <option {{ count($variants) > 0 ? '' : 'selected' }} value="0">{{ $baseName }}</option>
  @foreach ($variants as $variant)
    <option value="{{ $variant['id'] }}">{{ $variant['name'] }}</option>
  @endforeach
</select>

