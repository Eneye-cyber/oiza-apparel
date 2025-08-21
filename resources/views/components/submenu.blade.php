@foreach ($subcategories as $subcategory)
    <li class="flex flex-col w-full">
        <a href="{{ url('/shop/' . $subcategory['slug'] ) }}"
            class="text-center md:text-left bg-white block py-2.5 md:pr-8 text-sm font-semibold opacity-80 md:hover:text-primary md:hover:font-semibold hover:opacity-100">
            {{ $subcategory['name'] }}
        </a>
        <!-- Subcategories (indented, slightly smaller on mobile) -->
        @if (!empty($subcategory['subcategories']))
            @foreach ($subcategory['subcategories'] as $category)
                <a href="{{ $category['slug'] }}"
                    class="hidden md:block text-center md:text-left bg-white py-1 md:pr-8 text-xs md:text-sm opacity-60 md:hover:text-primary md:hover:font-semibold hover:opacity-100 hover:font-semibold">
                    {{ $category['name'] }}
                </a>
            @endforeach
        @endif
    </li>
@endforeach
