@props(['variants'])

@php
  $attributes = collect($variants)->flatMap(fn($variant) => $variant['attributes'])->groupBy('attribute_name');
@endphp

<div class="flex flex-col gap-4">
  @foreach ($attributes as $attrName => $attrValues)
    @php
      $uniqueValues = $attrValues->unique('attribute_value')->values();
      $isColor = $uniqueValues->every(fn($attr) =>
        preg_match('/^#[0-9A-Fa-f]{6}$|^[a-zA-Z]+$/i', $attr->attribute_value) &&
        !in_array(strtolower($attr->attribute_value), ['small','medium','large','s','m','l'])
      );
      $slug = Str::slug($attrName);
    @endphp

    @if ($uniqueValues->isNotEmpty())
      <div class="flex flex-col gap-2">
        <label class="text-sm font-semibold opacity-80">{{ $attrName }}:</label>
        <div class="flex gap-2">
          @foreach ($uniqueValues as $index => $attrValue)
            <button type="button"
                    class="{{ $isColor ? 'color-swatch w-8 h-8 rounded-full border-2' : 'attr-button px-4 py-2 text-sm border border-slate-200 rounded' }}
                           {{ $index === 0 ? ($isColor ? 'border-gold' : 'bg-black text-white') : ($isColor ? 'border-transparent' : 'bg-white') }}
                           hover:{{ $isColor ? 'border-gold' : 'bg-black text-white' }}
                           transition {{ $slug }}-option"
                    @if ($isColor) style="background-color: {{ strtolower($attrValue->attribute_value) }}" @endif
                    data-value="{{ $attrValue->attribute_value }}">
              <span class="{{ $isColor ? 'sr-only' : '' }}">{{ $attrValue->attribute_value }}</span>
            </button>
          @endforeach
        </div>
        <input type="hidden" id="{{ $slug }}" value="{{ $uniqueValues->first()->attribute_value ?? '' }}">
      </div>
    @endif
  @endforeach
</div>
